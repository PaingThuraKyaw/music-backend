<?php

namespace App\Http\Controllers;

use App\Http\Resources\MusicResource;
use App\Models\Album;
use App\Models\artist;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MusicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $music = Music::query(); // Initialize a query builder instance

        if ($request->search) {
            $searchTerm = $request->search;
            $music->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('artist', function ($query) use ($searchTerm) {
                    $query->where('artist', 'like', "%$searchTerm%");
                });
        }

        // Paginate the results
        $music = $music->paginate($request->size); // Use paginate() here

        return response()->json([
            'message' => 'Music',
            'data' => MusicResource::collection($music),
            'pagination' => [
                'page' => $music->currentPage(),
                'totalPage' => $music->total(),
                'size' => $music->perPage(),
            ]
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

        $art = artist::find($request->artist_id);
         if(!$art){
            return response()->json([
                'message' => 'Artist Not Found'
            ]);
         }


         $album = Album::find($request->album_id);
         if(!$album){
            return response()->json([
                'message' => 'Album Not Found'
            ],404);
         }

        $music = new Music();
        $music->name = $request->name;
        $music->description = $request->description;
        $music->artist_id = $art->id;
        $music->song_mp3 = ''; // Or provide some other default value


        if ($request->file('song_mp3')) {
            $songMp3 = $request->file('song_mp3');
            $songMp3Name = time() . '_' . rand() . $songMp3->getClientOriginalName();
            $audio =  $songMp3->storeAs('public/music/audio', $songMp3Name);
            $music->song_mp3 = $audio;
        }

        if ($request->hasFile('song_image')) {
            $songImage = $request->file('song_image');
            $songImageName = time() . '_' . rand() . $songImage->getClientOriginalName();
            $image =  $songImage->storeAs('public/music/images', $songImageName);
            $music->song_image = $image;
        }
        $music->album_id = $album->id;
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
