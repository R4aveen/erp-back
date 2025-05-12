<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioInvitadoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_invitado_debe_activar_su_cuenta_para_continuar()
    {
        // Crear usuario con cuenta no activada
        $usuario = Usuario::factory()->create([
            'password' => bcrypt('temporal123'),
            'activado' => false,
        ]);

        // Login con JWT
        $response = $this->postJson('/api/login', [
            'email' => $usuario->email,
            'password' => 'temporal123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
        $token = $response->json('token');

        // Intentar acceder a un endpoint protegido con middleware VerificarActivacion
        $res = $this->withHeader('Authorization', 'Bearer ' . $token)
                    ->getJson('/api/perfil');

        $res->assertStatus(403)
            ->assertJson(['error' => 'Debes activar tu cuenta cambiando tu contraseña.']);
    }

    /** @test */
    public function un_usuario_puede_activar_su_cuenta_con_password_valido()
    {
        $usuario = Usuario::factory()->create([
            'password' => bcrypt('temporal123'),
            'activado' => false,
        ]);

        $token = JWTAuth::fromUser($usuario);
        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/activar-cuenta', [
                             'password' => 'nuevofuerte123',
                             'password_confirmation' => 'nuevofuerte123',
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['mensaje' => 'Cuenta activada correctamente.']);

        $this->assertTrue(Hash::check('nuevofuerte123', $usuario->fresh()->password));
        $this->assertDatabaseHas('usuarios', [
            'id' => $usuario->id,
            'activado' => true,
        ]);

    }
}
