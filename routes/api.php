<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/data/{code}', function ($code) {

    if (!$code == "46246321")
        return response()->json(["error" => "Unauthorized"], 401);

    $groupedData = DB::raw("SELECT * FROM users GROUP BY fso_name;");

    return response()->json($groupedData);

});
