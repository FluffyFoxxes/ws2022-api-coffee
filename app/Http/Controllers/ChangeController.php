<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\ChangeUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChangeController extends Controller
{
    public function createChange(Request $request)
    {

        $bearer = $request->header('authorization');
        if ($bearer != "") {
            $token = explode(" ", $bearer)[1];
            $user = User::all()->where('token', $token)->first();
            if ($user != null) {
                if ($user->group == "Администраторы") {
                    $validator = Validator::make($request->all(), [
                        "date" => "required|string",
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

                    $date = date('Y-m-d', strtotime($request->get("date")));
                    $code = bin2hex(openssl_random_pseudo_bytes(6));
                    $change = new Change([
                        "date" => $date,
                        "code" => $code,
                    ]);
                    $change->save();
                    return response()->json(
                        ["data" => ["code" => $code]]
                    );
                } else {
                    return response()->json(
                        ["code" => 403, "message" => "Forbidden for you"],
                        403
                    );
                }
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

    public function addUser(Request $request)
    {
        $bearer = $request->header('authorization');
        if ($bearer != "") {
            $token = explode(" ", $bearer)[1];
            $user = User::all()->where('token', $token)->first();
            if ($user != null) {
                if ($user->group == "Администраторы") {
                    $validator = Validator::make($request->all(), [
                        "change.id" => "required|integer",
                        "users.*" => "required|array|min:1",
                        "users.*.name" => "required|string",
                        "users.*.login" => "required|string",
                        "users.*.status" => "required|string",
                        "users.*.group" => "required|string",
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

                    $change = Change::all()->where("id", $request->get("change")["id"])->first();

                    if ($change != null) {
                        foreach($request->get("users") as $u) {
                            $user = User::all()->where("login", $u["login"])->first();
                            $change_users = new ChangeUsers([
                                "change_id" => $request->get("change")["id"],
                                "user_id" => $user->id
                            ]);
                            $change_users->save();
                        }
                        return response()->json(
                            ["data" => ["code" => $change->code]]
                        );
                    } else {
                        return response()->json([
                            "error" => [
                                "code" => 422,
                                "message" => "Validation error",
                                "errors" => ["change.id" => ["The change.id field not found."]]
                            ],
                        ], 422);
                    }
                } else {
                    return response()->json(
                        ["code" => 403, "message" => "Forbidden for you"],
                        403
                    );
                }
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
}
