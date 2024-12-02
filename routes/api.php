<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/data/{code}', function ($code) {

    if (!$code == "46246321")
        return response()->json(["error" => "Unauthorized"], 401);


    $groupedData = DB::table('your_table_name')
        ->select('fso_name', DB::raw('
        JSON_ARRAYAGG(JSON_OBJECT(
            "id", id,
            "name", name,
            "email", email,
            "phone", phone,
            "language", language,
            "credentials", credentials,
            "profile", profile,
            "fso_emp_id", fso_emp_id
        )) as details
    '))
        ->groupBy('fso_name')
        ->get();

    return response()->json($groupedData);

});
