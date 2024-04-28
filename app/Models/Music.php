<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    public function artist()
    {
        $this->belongsTo(artist::class);
    }

    public function album()
    {
        $this->belongsTo(Album::class);
    }

    protected $fillable = [
        'name',
        'song_mp3',
        'description',
        'song_image',
        'artist_id'
    ];
}
