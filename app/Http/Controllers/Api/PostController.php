<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        $res = [
            'success' => true,
            'data' => $posts,
            'message' => 'List Post',
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:posts',
            'content' => 'required|string|max:255',
            'status' => 'required',
            'foto' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $post =          new Post;
        $post ->title    = $request->title;
        $post ->content  = $request->content;
        $post ->slug     = Str::slug($request->title, '-');
        $post ->status   = $request->status;

        // image
        if($request->hasFile('foto')){
            $path = $request->file('foto')->store('posts','public');
            $post->foto = $path;
        }
        $post->save();

        // Response
        $res = [
            'success' => true,
            'data' => $post,
            'message' => 'Store Post'
        ];
        return response()->json($res, 200);
    }

    public function show(Request $request, $id)
    {
       $post = Post::find($id);
       if (! $post) {
        return response()->json([
            'message' => 'Data not found',
        ], 400);
       }

       return response()->json([
        'success'=> true,
        'data' => $post,
        'message' => 'Show Post Detail'
       ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:posts,id',
            'content' => 'required|string|max:255',
            'status' => 'required',
            'foto' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $post            = Post::find($id);
        $post ->title    = $request->title;
        $post ->content  = $request->content;
        $post ->slug     = Str::slug($request->title, '-');
        $post ->status   = $request->status;

        // image
        if($request->hasFile('foto')){
            if($post->foto && Storage::disk('public')->exists($post->foto)){
            // delete foto lama
            Storage::disk('public')->delete($post->foto);

            // upload foto baru
            $path = $request->file('foto')->store('posts','public');
            $post->foto = $path;
            }
        }
        $post->save();

        // Response
        $res = [
            'success' => true,
            'data' => $post,
            'message' => 'Store Post'
        ];
        return response()->json($res, 200);
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'message' => 'Data not found',
            ],400);
        }

        // delete foto di storage
        if($request->hasFile('foto')){
            if($post->foto && Storage::disk('public')->exists($post->foto)) {
                Storage::disk('public')->delete($post->foto);
            }
        }

        $post->delete();
        return response()->json([
            'success'=> true,
            'message' => 'post deleted successfully'
        ], 200);
    }
}