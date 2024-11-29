<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PosterController extends Controller
{
    static function home($user_id): View
    {
        $user = DB::table('users')->where('id', $user_id)->first();
        $profile = asset('storage/' . $user->profile);
        $poster = asset('storage/posters/uterine_fibroid/' . $user->language . ".png");
        return view('poster', [
            'name' => $user->name,
            'profile' => $profile,
            'poster' => $poster,
            'language' => $user->language,
            'credentials' => $user->credentials,
        ]);
    }

}
