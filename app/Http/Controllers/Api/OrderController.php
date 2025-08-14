<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $orders = Order::with(['event', 'tiket'])
                    ->where('user_id', $userId)
                    ->latest()
                    ->get();

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
            // 'user_id'=> 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
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
        $order ->user_id        = $request->user()->id;
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

        // Buat tiket otomatis
        for ($i = 0; $i < $order->quantity; $i++) {
            $order->tiket()->create([
                'user_id' => $order->user_id,
                'event_id' => $order->event_id,
                'name' => $order->name . '-' . ($i + 1),
                'location' => $order->location,
                'code' => $order->code . '-' . Str::random(4),
                'start_date' => $order->start_date,
                'end_date' => $order->end_date,
            ]);
        }    

        // Ambil relasi
        $order = Order::with(['event', 'tiket'])->find($order->id);

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
        $userId = $request->user()->id;
        $order = Order::with(['event', 'tiket'])
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();

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
        'status' => 'required|in:pending,paid,cancelled',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $order = Order::find($id);
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    $order->status = $request->status;
    $order->save();

    // Ambil relasi
    $order = Order::with('event')->find($order->id);

    return response()->json([
        'success' => true,
        'data' => $order,
        'message' => 'Order status updated'
    ], 200);
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
