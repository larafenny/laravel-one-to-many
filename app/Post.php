<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Post extends Model
{
    protected $fillable = ['title', 'content', 'slug', 'category_id'];

    //mappiamo relazione inversa di category per unire le due tabelle
    public function category(){
        return $this->belongsTo('App\Category');
    }

    //input una stringa e restituisce uno slug univoco
    public static function convertToSlug($title){
        $slugPrefix = Str::slug($title);

        $slug = $slugPrefix;
        $postFound = Post::where('slug', $slug)->first();
        $counter = 1;

        while($postFound){
            $slug = $slugPrefix . '_' . $counter;
            $counter++;
            $postFound = Post::where('slug', $slug)->first();
        }
        return $slug;
    }
}
