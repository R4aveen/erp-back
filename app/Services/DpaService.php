<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DpaService
{
    protected $base = 'https://apis.digital.gob.cl/dpa/';

    public function regiones(): array
    {
        return $this->get('regiones');
    }

    public function provincias(?string $regionId = null): array
    {
        $uri = $regionId
          ? "regiones/{$regionId}/provincias"
          : 'provincias';

        return $this->get($uri);
    }

    public function comunas(?string $provinciaId = null): array
    {
        $uri = $provinciaId
          ? "provincias/{$provinciaId}/comunas"
          : 'comunas';

        return $this->get($uri);
    }

    protected function get(string $path): array
    {
        $resp = Http::baseUrl($this->base)
                    ->timeout(5)
                    ->get($path);

        if ($resp->successful()) {
            return $resp->json();
        }

        abort(502, "Error al consultar DPA: {$path}");
    }
}
