<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artista extends Model
{
    protected $table = "artists";
    protected $primaryKey = "ArtistId";
    public $timestamp = false;


    public function albumes(){
        return $this->hasMany('App\Album' , 'ArtistId');
    }
}