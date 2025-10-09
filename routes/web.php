<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scramble/login', function () {
    return view('scramble.login');
});
Route::post('/scramble/login',function (){
    $credentials = request()->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    if ($credentials['username'] === config('scramble.username') && $credentials['password'] === config('scramble.password')) {
        session()->put('scramble_login', true);
        return redirect('/docs/api');
    }

    return back()->withErrors(['username' => 'Invalid credentials.']);
});
