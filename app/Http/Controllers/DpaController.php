<?php namespace App\Http\Controllers;

use App\Services\DpaService;
use Illuminate\Support\Facades\Cache;

class DpaController extends Controller
{
    protected $dpa;
    public function __construct(DpaService $dpa)
    {
        $this->dpa = $dpa;
    }

    public function regiones()
    {
        $key = 'dpa_regiones';
        $data = Cache::remember($key, 60*24, fn() => $this->dpa->regiones());
        return response()->json($data);
    }

    public function provincias($regionId = null)
    {
        $key = "dpa_provincias_{$regionId}";
        $data = Cache::remember($key, 60*24, fn() => $this->dpa->provincias($regionId));
        return response()->json($data);
    }

    public function comunas($provinciaId = null)
    {
        $key = "dpa_comunas_{$provinciaId}";
        $data = Cache::remember($key, 60*24, fn() => $this->dpa->comunas($provinciaId));
        return response()->json($data);
    }
}
