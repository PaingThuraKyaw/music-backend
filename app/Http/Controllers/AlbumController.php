<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'album' => 'required|unique:albums,album',
            'album_image' => 'required|image',
            'artist_id' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()
            ]);
        };

        $art =  artist::find($request->artist_id);
        if (!$art) {
            return response()->json([
                'message' => 'Artist Not Found'
            ]);
        }


        $album = new Album();
        $album->album = $request->album;
        $album->artist_id = $request->artist_id;
        if ($request->hasFile('album_image')) {
            $image = $request->file('album_image');
            $imageStore = $image->store('public/artist');
            $album->album_image = $imageStore;
        }
        $album->save();

        return response()->json([
            'message' => 'Successfully Album create',
            'data' =>  $album
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        //
    }
}
