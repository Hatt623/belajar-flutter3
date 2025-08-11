<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();
        $res = [
            'success' => true,
            'data' => $orders,
            'message' => 'List order',
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255|unique:orders',
            'location' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:orders',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|integer',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $order =                new Order;
        $order ->user_id        = $request->user_id;
        $order ->event_id       = $request->event_id;
        $order ->name           = $request->name;
        $order ->location       = $request->location;
        $order ->code           = $request->code;
        $order ->start_date     = $request->start_date;
        $order ->end_date       = $request->end_date;
        $order ->quantity       = $request->quantity;
        $order ->price          = $request->price;
        $order ->status         = $request->status;

        $order->save();

        // Response
        $res = [
            'success' => true,
            'data' => $order,
            'message' => 'Store order'
        ];
        return response()->json($res, 200);
    }

    public function show(Request $request, $id)
    {
       $order = Order::find($id);
       if (! $order) {
        return response()->json([
            'message' => 'Data not found',
        ], 400);
       }

       return response()->json([
        'success'=> true,
        'data' => $order,
        'message' => 'Show order Detail'
       ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255|unique:orders,id',
            'location' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:orders,id',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|integer',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $order                  = Order::find($id);
        $order ->user_id        = $request->user_id;
        $order ->event_id       = $request->event_id;
        $order ->name           = $request->name;
        $order ->location       = $request->location;
        $order ->code           = $request->code;
        $order ->start_date     = $request->start_date;
        $order ->end_date       = $request->end_date;
        $order ->quantity       = $request->quantity;
        $order ->price          = $request->price;
        $order ->status         = $request->status;

        $order->save();

        // Response
        $res = [
            'success' => true,
            'data' => $order,
            'message' => 'Store order'
        ];
        return response()->json($res, 200);
    }

    public function destroy(Request $request, $id)
    {
        $order = Order::find($id);

        if (! $order) {
            return response()->json([
                'message' => 'Data not found',
            ],400);
        }

        // delete image di storage
        if($request->hasFile('image')){
            if($order->image && Storage::disk('public')->exists($order->image)) {
                Storage::disk('public')->delete($order->image);
            }
        }

        $order->delete();
        return response()->json([
            'success'=> true,
            'message' => 'order deleted successfully'
        ], 200);
    }
}
