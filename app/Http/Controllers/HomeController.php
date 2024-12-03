<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    static function home(): Response|View
    {

        if (Carbon::now() > Carbon::make("01/01/2025"))
            return response([
                'status' => false,
                'message' => 'Licence expired contact qurkos.com for further assistance!'
            ]);

        return view('home', [
            'languages' => [
                'HINDI',
                'ENGLISH',
                'BENGALI',
                'ASSAMESE',
                'GUJARATI',
                'KANNADA',
                'MALAYALAM',
                'MARATHI',
                'ORIYA',
                'PUNJABI',
                'TAMIL',
                'TELUGU',
            ]
        ]);
    }

    static function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'credentials' => 'required|string|max:255',
            'language' => 'required|string',
            'fso_name' => 'required|string',
            'fso_emp_id' => 'required|string',
        ], [
            'name.required' => 'Name field is required.',
            'email.required' => 'Email field is required.',
            'phone.required' => 'Phone Number field is required.',
            'credentials.required' => 'Credentials field is required.',
            'language.required' => 'Language field is required.',
            'language.fso_name' => 'Please enter valid FSO name',
            'language.fso_emp_id' => 'Please enter valid FSO employee id',
        ]);

        // CHECK FILE
        if (!$request->hasFile('profile'))
            return back()->with('error', ['profile' => 'Please upload profile image.']);
        if (!$request->file('profile')->isValid())
            return back()->with('error', ['profile' => 'Please upload valid profile image.']);

        // CREATE IMAGE NAME
        $profilePicture = $request->file('profile');
        $profilePictureName = time() . '_' . $validated['phone'] . '.' . $profilePicture->getClientOriginalExtension();

        // STORE IMAGE
        $path = $profilePicture->storeAs('images', $profilePictureName);

        // SAVE USER
        DB::table('users')->updateOrInsert(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'fso_name' => $validated['fso_name'],
                'fso_emp_id' => $validated['fso_emp_id'],
                'credentials' => $validated['credentials'],
                'language' => $validated['language'],
                'profile' => $path,
                'password' => Hash::make($validated['phone']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $user = DB::table('users')->where('phone', $validated['phone'])->first();

        return redirect()->route(
            'poster', ["user_id" => $user->id]
        )->with('success', 'Profile updated successfully.');

    }

}
