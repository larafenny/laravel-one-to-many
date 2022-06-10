<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //per gestire l'irregolarità del plurale
    protected $table = 'categories';

    //funzione che identifica la tabella post
    public function posts(){
        return $this->hasMany('App\Post');
    }

}
