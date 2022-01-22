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
            return response()->json(
                ["error" => ["code" => 401, "message" => "Authentication failed"]],
                401
            );
        }

        $user = User::all()->where('login', $request->input("login"))->first();

        if ($user != null) {
            if ($user->password == $request->input("password")) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $user->token = $token;
                $user->save();
                return response()->json(
                    ["data" => ["user_token" => $token]]
                );
            } else {
                return response()->json(
                    ["error" => ["code" => 401, "message" => "Authentication failed"]],
                    401
                );
            }
        } else {
            return response()->json(
                ["error" => ["code" => 401, "message" => "Authentication failed"]],
                401
            );
        }
    }

    public function logout(Request $request)
    {
        $bearer = $request->header('authorization');
        if ($bearer != "") {
            $token = explode(" ", $bearer)[1];
            $user = User::all()->where('token', $token)->first();
            if ($user != null) {
                $user->token = "";
                $user->save();
                return response()->json(
                    ["data" => ["message" => "logout"]]
                );
            } else {
                return response()->json(
                    ["code" => 403, "message" => "Login failed"],
                    403
                );
            }
        } else {
            return response()->json(
                ["code" => 403, "message" => "Login failed"],
                403
            );
        }
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
