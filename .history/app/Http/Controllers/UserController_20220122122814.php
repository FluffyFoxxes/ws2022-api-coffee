<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class UserController extends Controller
{
    public function login(Request $request) {
        try {
            $validation = FacadesValidator::make($request, [
                'login' => 'required|string',
                'password' => 'required|string'
            ]);

            return response()->json($validation);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "errors" => $th->error_reporting
            ]);
        }
    }
}
