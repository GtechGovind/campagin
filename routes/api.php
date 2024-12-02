<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/data/{code}', function ($code) {

    if (!$code == "46246321")
        return response()->json(["error" => "Unauthorized"], 401);


    $groupedData = DB::table('your_table_name')
        ->select()
        ->groupBy('fso_name')
        ->get();

    return response()->json($groupedData);

});
