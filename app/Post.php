<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'slug'];

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
