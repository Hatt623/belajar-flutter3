<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();
        $res = [
            'success' => true,
            'data' => $events,
            'message' => 'List event',
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:events',
            'start_date' => 'required',
            'end_date' => 'required',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:4048',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $event =                new Event;
        $event ->name           = $request->name;
        $event ->start_date     = $request->start_date;
        $event ->end_date       = $request->end_date;
        $event ->location       = $request->location;
        $event ->description    = $request->description;
        $event ->price          = $request->price;

        // image
        if($request->hasFile('image')){
            $path = $request->file('image')->store('events','public');
            $event->image = $path;
        }
        $event->save();

        // Response
        $res = [
            'success' => true,
            'data' => $event,
            'message' => 'Store event'
        ];
        return response()->json($res, 200);
    }

    public function show(Request $request, $id)
    {
       $event = Event::find($id);
       if (! $event) {
        return response()->json([
            'message' => 'Data not found',
        ], 400);
       }

       return response()->json([
        'success'=> true,
        'data' => $event,
        'message' => 'Show event Detail'
       ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:events,id',
            'start_date' => 'required',
            'end_date' => 'required',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:4048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $event                  = Event::find($id);
        $event ->name           = $request->name;
        $event ->start_date     = $request->start_date;
        $event ->end_date       = $request->end_date;
        $event ->location       = $request->location;
        $event ->description    = $request->description;
        $event ->price          = $request->price;
        // image
        if($request->hasFile('image')){
            if($event->image && Storage::disk('public')->exists($event->image)){
            // delete image lama
            Storage::disk('public')->delete($event->image);

            // upload image baru
            $path = $request->file('image')->store('events','public');
            $event->image = $path;
            }
        }
        $event->save();

        // Response
        $res = [
            'success' => true,
            'data' => $event,
            'message' => 'Store event'
        ];
        return response()->json($res, 200);
    }

    public function destroy(Request $request, $id)
    {
        $event = Event::find($id);

        if (! $event) {
            return response()->json([
                'message' => 'Data not found',
            ],400);
        }

        // delete image di storage
        if($request->hasFile('image')){
            if($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
        }

        $event->delete();
        return response()->json([
            'success'=> true,
            'message' => 'event deleted successfully'
        ], 200);
    }
}
