<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(Request $request) {}

    public function showOrder(Request $request, $code) {}

    public function changeStatus(Request $request, $code) {}

    public function addDish(Request $request, $code) {}

    public function deleteDish(Request $request, $code) {}
}
