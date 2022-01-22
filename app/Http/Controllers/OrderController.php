<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Change;
use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderDishs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Translation\Provider\Dsn;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $bearer = $request->header('authorization');
        if ($bearer != "") {
            $token = explode(" ", $bearer)[1];
            $user = User::all()->where('token', $token)->first();
            if ($user != null) {
                if ($user->group == "Официант") {
                    $validator = Validator::make($request->all(), [
                        "book_id" => "required|integer",
                        "orders.*" => "required|array|min:1",
                        "orders.*.id" => "required|integer",
                        "orders.*.dish" => "required|string",
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

                    $book = Book::all()->where("id", $request->get("book_id"))->first();
                    if ($book != null) {
                        $code = bin2hex(openssl_random_pseudo_bytes(6));
                        $order = new Order([
                            "book_id" => $book->id,
                            "status" => "готовится",
                            "code" => $code
                        ]);
                        $order->save();
                        foreach ($request->get("orders") as $o) {
                            $dish = Dish::all()->where("id", $o["id"])->first();
                            $order_dishs = new OrderDishs([
                                "order_id" => $order->id,
                                "dish_id" => $dish->id
                            ]);
                            $order_dishs->save();
                        }
                        return response()->json(
                            ["data" => ["code" => $order->code]]
                        );
                    } else {
                        return response()->json([
                            "error" => [
                                "code" => 422,
                                "message" => "Validation error",
                                "errors" => ["book_id" => ["The book_id field not found."]]
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

    public function showOrder(Request $request, $code)
    {
    }

    public function showOrders(Request $request, $code)
    {
        $bearer = $request->header('authorization');
        if ($bearer != "") {
            $token = explode(" ", $bearer)[1];
            $user = User::all()->where('token', $token)->first();
            if ($user != null) {
                if ($user->group == "Администраторы") {
                    $change = Change::all()->where("code", $code)->first();
                    if ($change != null) {
                        return response()->json(
                            ["data" => "not implemented!!!"]
                        );
                    } else {
                        return response()->json(
                            ["code" => 403, "message" => "Change not found."],
                            403
                        );
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

    public function changeStatus(Request $request, $code)
    {
    }

    public function addDish(Request $request, $code)
    {
        $bearer = $request->header('authorization');
        if ($bearer != "") {
            $token = explode(" ", $bearer)[1];
            $user = User::all()->where('token', $token)->first();
            if ($user != null) {
                if ($user->group == "Официант") {
                    $validator = Validator::make($request->all(), [
                        "orders.*" => "required|array|min:1",
                        "orders.*.id" => "required|integer",
                        "orders.*.dish" => "required|string",
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

                    $order = Order::all()->where("code", $code)->first();
                    if ($order != null) {
                        foreach ($request->get("orders") as $o) {
                            $dish = Dish::all()->where("id", $o["id"])->first();
                            $order_dishs = new OrderDishs([
                                "order_id" => $order->id,
                                "dish_id" => $dish->id
                            ]);
                            $order_dishs->save();
                        }
                        return response()->json(
                            ["data" => ["code" => $order->code]]
                        );
                    } else {
                        return response()->json(
                            ["code" => 404, "message" => "Not found order"],
                            403
                        );
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

    public function deleteDish(Request $request, $code)
    {
    }
}
