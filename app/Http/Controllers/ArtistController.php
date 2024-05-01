<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArtistResource;
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
            'data' => ArtistResource::collection($artist->all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'artist' => 'required|unique:artists,artist',
            'artist_image' => 'required|image',
            'about' => 'required',
            'birth' => 'required'

        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()
            ]);
        };


        try {
            $artist = new artist();
            $artist->artist = $request->artist;
            $artist->about = $request->about;
            $artist->birth = $request->birth;
            if ($request->hasFile('artist_image')) {
                $image = $request->file('artist_image');
                // $fileName = time() . '_' . $image->getClientOriginalName();
                $imageStore = $image->store('public/artist');
                $artist->artist_image = $imageStore;
            }
            $artist->save();

            return response()->json([
                'message' => "Artist successfully create",
                'data' => $artist
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 500);
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
