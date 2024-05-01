<?php

namespace App\Http\Resources;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $album = Album::where('artist_id','=',$this->id)->get();

        return [
            'id' => $this->id,
            'artist' => $this->artist,
            'artist_image' => asset(str_replace('public', 'storage', $this->artist_image)),
            'about' => $this->about,
            'birth' => $this->birth,
            'album' => $album,
        ];
    }
}
