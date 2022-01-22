<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request) {
        try {
            $validation = $this->validate($request, [
                'login' => 'required|string',
                'password' => 'required|string'
            ]);

            return response()->json($validation);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
