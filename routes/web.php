<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['mensaje' => 'API ERP disponible']);
});

Route::get('/debug-feats', function(){
    return \App\Models\Feature::pluck('clave');
});