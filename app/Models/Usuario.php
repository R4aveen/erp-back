<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Models\Rol;
use App\Models\Permiso;
use App\Models\PersonalizacionUsuario;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class Usuario extends Authenticatable implements JWTSubject
    {
        use Notifiable;

        protected $table    = 'usuarios';
        protected $fillable = ['nombre', 'email', 'password', 'activado', 'token_activacion'];
        protected $hidden   = ['password', 'token_activacion'];

        public function getJWTIdentifier() { return $this->getKey(); }
        public function getJWTCustomClaims(): array { return []; }

        public function roles()
        {
            return $this->belongsToMany(
                Rol::class, 
                'empresa_usuario_rol', 
                'usuario_id', 
                'rol_id'
                )->withPivot('empresa_id')->withTimestamps();
        }

        public function permisosDirectos()
        {
            return $this->belongsToMany(
                Permiso::class, 
                'permiso_usuario', 
                'usuario_id', 
                'permiso_id'
                )->withTimestamps();
        }
        
        public function obtenerPermisos(): array
        {
            /// me aseguro de tener cargada las dos relaciones primeroo
            $this->loadMissing((['roles.permisos', 'permisosDirectos']));

            // 1) permisos por rol
            $viaRol = $this->roles->flatMap(fn($rol) => $rol-> permisos->pluck('clave'))->toArray();

           // 2) ahora los permisos directos
           $directos = $this->permisosDirectos
                ->pluck('clave')
                ->toArray();

            // 3) los junto y elimino duplicados
            return array_values(array_unique(array_merge($viaRol, $directos)));
            // $permisos = array_merge($viaRol, $directos);
            // return array_values(array_unique($permisos));   
        }

        public function personalizacion()
        {
            return $this->hasOne(PersonalizacionUsuario::class, 'usuario_id');
        }

        public function permisos(): array
        {
            $this->loadMissing(['roles.permisos', 'permisosDirectos']);

            $viaRol = $this->roles->flatMap(fn($rol) => $rol->permisos)->pluck('clave')->toArray();
            $directos = $this->permisosDirectos->pluck('clave')->toArray();

            return array_values(array_unique(array_merge($viaRol, $directos)));
        }

        public function tienePermiso(string $clave): bool
        {
            $perms = $this->permisos();
            return in_array($clave, $perms) || in_array(explode(':', $clave)[0] . ':*', $perms);
        }

        public function empleado()
        {
            return $this->hasOne(Empleado::class);
        }

        public function empresasRoles(): BelongsToMany
        {
            return $this->belongsToMany(
                Empresa::class,              // el modelo relacionado
                'empresa_usuario_rol',       // nombre de la tabla pivote
                'usuario_id',                // FK de este modelo en la pivote
                'empresa_id'                 // FK del modelo Empresa en la pivote
            )
            ->withPivot('rol_id')           // para poder leer/escribir el rol
            ->using(\App\Models\EmpresaUsuarioRol::class); // opcional si usas un Pivot custom
        }

        public function empresas()
        {
            return $this->belongsToMany(
                Empresa::class,
                'empresa_usuario_rol', // << aquí el nombre correcto de la tabla pivote
                'usuario_id',          // FK usuario en esa tabla
                'empresa_id'           // FK empresa en esa tabla
            )
            ->withPivot('rol_id')
            ->withTimestamps();
        }

    // Si también necesitas las sucursales:
    public function sucursales()
    {
        return $this->belongsToMany(
            Sucursal::class,
            'sucursal_usuario',
            'usuario_id',
            'sucursal_id'
        )
        ->withTimestamps();
    }
    public function subempresas()
    {
        return $this->belongsToMany(
            Subempresa::class,
            'subempresa_usuario',
            'usuario_id',
            'subempresa_id'
        )
        ->withTimestamps();
    }
}

