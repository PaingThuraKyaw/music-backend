<?php

namespace App\Http\Controllers;

use App\Http\Resources\MusicResource;
use App\Models\artist;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MusicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $music = new Music();
        if (request()->search) {
            $searchTerm = request()->search;
            // $music = $music->whereHas('artist', function ($query) use ($searchTerm) {
            //     $query->where('artist', 'like', "%$searchTerm%");
            // });

            $music = $music->where('name', 'like', '%' . $searchTerm . '%')->orWhereHas('artist',function($query) use($searchTerm) {
                $query->where('artist','like',"%$searchTerm%");
            });

            return response()->json([
                'message' => 'Music',
                'data' => MusicResource::collection($music->get())
            ]);
        }
        return response()->json([
            'message' =>  'Music!',
            'data' => MusicResource::collection($music->all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'song_mp3' => 'required',
            'description' => 'required',
            'song_image' => 'required|image',
            'album_id' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()
            ]);
        }

        $music = new Music();
        $music->name = $request->name;
        $music->description = $request->description;
        $music->artist_id = $request->artist_id;
        $music->song_mp3 = ''; // Or provide some other default value


        if ($request->file('song_mp3')) {
            $songMp3 = $request->file('song_mp3');
            $songMp3Name = time() . '_' . $songMp3->getClientOriginalName();
            $audio =  $songMp3->storeAs('public/music/audio', $songMp3Name);
            $music->song_mp3 = $audio;
        }

        if ($request->hasFile('song_image')) {
            $songImage = $request->file('song_image');
            $songImageName = time() . '_' . $songImage->getClientOriginalName();
            $image =  $songImage->storeAs('public/music/images', $songImageName);
            $music->song_image = $image;
        }
        $music->album_id = $request->album_id;
        $music->save();


        return response()->json([
            'message' => 'Music created successfully',
            'data' => $music
        ]);
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
