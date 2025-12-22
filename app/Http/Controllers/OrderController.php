<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function apply(Request $request, Order $order)
    {
        $discount = 10;  // for example 10$

        $order->increment('discunt_amount', $discount);

        return response()->json([
           'order_id'           => $order->id,
           'discunt_amount'    => $discount,
           'total_amount'       => $order->total_amount - $discount,
        ],200);
        
    }
}
