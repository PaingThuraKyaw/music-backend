<?php

namespace App\Http\Controllers;

use App\Models\artist;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artist = new artist();
        return response()->json([
            'message' => 'All artists',
            'data' => $artist->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'artist' => 'required',
            'artist_image' => 'required|image'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()
            ]);
        };


        try {
            $artist = new artist();
            $artist->artist = $request->artist;
            if ($request->hasFile('artist_image')) {
                $image = $request->file('artist_image');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $imageStore = $image->storeAs('public/artist', $fileName);
                $artist->artist_image = $imageStore;
            }
            $artist->save();

            return response()->json([
                'message' => "Artist successfully create",
                'data' => $artist
            ]);
        } catch (Exception $e) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}


// public/1714190816_356379691_810884177240139_2585504872274285403_n.jpg
