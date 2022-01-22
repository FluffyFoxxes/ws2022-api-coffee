<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request) {
        $this->validate($request, [
            'login' => 'required|string',
            'password' => 'required|string'
        ]);


    }
}
