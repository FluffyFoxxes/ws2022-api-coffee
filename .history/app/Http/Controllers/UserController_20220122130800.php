<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "login" => "required|string",
            "password" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => [
                    "code" => 401,
                    "message" => "Authentication failed"
                ]
            ], 401);
        }

        // $user = User::all()->find

        return response()->json("SUCCESS");
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "login" => "required|string",
            "status" => "required|string",
            "group" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => [
                    "code" => 422,
                    "message" => "Validation error",
                    "errors" => $validator->errors()
                ],
            ], 422);
        }

        $password = bin2hex(openssl_random_pseudo_bytes(16));

        return response()->json([
            "data" => [
                "code" => $password
            ]
        ]);
    }
}
