<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TiketController extends Controller
{
    public function index()
    {
        $tikets = Tiket::with(['event', 'order'])->latest()->get();
        $res = [
            'success' => true,
            'data' => $tikets,
            'message' => 'List tiket',
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'order_id' => 'required|exists:orders,id',
            'name' => 'required|string|max:255|unique:tikets',
            'location' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:tikets',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $tiket =                new Tiket;
        $tiket ->user_id        = $request->user_id;
        $tiket ->event_id       = $request->event_id;
        $tiket ->order_id       = $request->order_id;
        $tiket ->name           = $request->name;
        $tiket ->location       = $request->location;
        $tiket ->code           = $request->code;
        $tiket ->start_date     = $request->start_date;
        $tiket ->end_date       = $request->end_date;

        $tiket->save();
        $tiket = Tiket::with(['event', 'order'])->find($tiket->id);

        // Response
        $res = [
            'success' => true,
            'data' => $tiket,
            'message' => 'Store tiket'
        ];
        return response()->json($res, 200);
    }

    public function show(Request $request, $id)
    {
       $tikets = Tiket::with(['event', 'order'])->latest()->get();
       if (! $tiket) {
        return response()->json([
            'message' => 'Data not found',
        ], 400);
       }

       return response()->json([
        'success'=> true,
        'data' => $tiket,
        'message' => 'Show tiket Detail'
       ], 200);
    }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_id'=> 'required|exists:users,id',
    //         'event_id' => 'required|exists:events,id',
    //         'order_id' => 'required|exists:orders,id',
    //         'name' => 'required|string|max:255|unique:tikets,id',
    //         'location' => 'required|string|max:255',
    //         'code' => 'required|string|max:255|unique:tikets,id',
    //         'start_date' => 'required|string',
    //         'end_date' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(),400);
    //     }

    //     $tiket                  = Tiket::find($id);
    //     $tiket ->user_id        = $request->user_id;
    //     $tiket ->event_id       = $request->event_id;
    //     $tiket ->order_id       = $request->order_id;
    //     $tiket ->name           = $request->name;
    //     $tiket ->location       = $request->location;
    //     $tiket ->code           = $request->code;
    //     $tiket ->start_date     = $request->start_date;
    //     $tiket ->end_date       = $request->end_date;


    //     $tiket->save();
    // $tiket = Tiket::with(['event', 'order'])->find($tiket->id);

    //     // Response
    //     $res = [
    //         'success' => true,
    //         'data' => $tiket,
    //         'message' => 'Store tiket'
    //     ];
    //     return response()->json($res, 200);
    // }

    public function destroy(Request $request, $id)
    {
        $tiket = Tiket::find($id);

        if (! $tiket) {
            return response()->json([
                'message' => 'Data not found',
            ],400);
        }

        // delete image di storage
        if($request->hasFile('image')){
            if($tiket->image && Storage::disk('public')->exists($tiket->image)) {
                Storage::disk('public')->delete($tiket->image);
            }
        }

        $tiket->delete();
        return response()->json([
            'success'=> true,
            'message' => 'tiket deleted successfully'
        ], 200);
    }
}
