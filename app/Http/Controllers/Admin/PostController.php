<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Post;
use App\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
   {
        //prendo tutte le categorie
        $categories = Category::all();

        //passiamo le categorie alla view e ritorna la view
        return view('admin.posts.create', compact('categories'));
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
   {
       $request->validate([
           'title'=> 'required|max:250',
           'content'=> 'required|min:5',
           'category' => 'required|exists:categories,id'
       ],
       [    //messaggi di errore dei requisiti sopra
           'title.required' =>'Titolo deve essere valorizzato.',
           'title.max' =>'Hai superato i 250 caratteri.',
           'content.required' => 'Il contenuto deve essere compilato.',
           'content.min' => 'Minimo 5 caratteri.',
           'category_id.exists' => 'La categoria selezionata non esiste'
       ]);

       $postData = $request->all();
       $newPost = new Post();
       $newPost->fill($postData);

       $newPost->slug = Post::convertToSlug($newPost->title);

       $slug = Str::slug($newPost->title);

       //creo e inizializzo variabile che userò per il ciclo while che verifica se slug è già stato usato e aggiungerà a quello nuovo un numero
       $alternativeSlug = $slug;
       //faccio query al db per vedere se c'è già uno slug uguale
       $postFound = Post::where('slug', $alternativeSlug)->first();
       //metto un counter
       $counter = 1;
       //faccio ciclo while per verificare se $postFound e quindi lo slug è presente nel DB, e quindi è già stato usato. se si aggiunge un numero
       /*
       while($postFound){
           //aggiunge _counter allo slug
           $alternativeSlug = $slug . '_' . $counter;
           //incrementa counter
           $counter++;
           //verifico se non essite un altro slug con il numero aggiunto
           $postFound = Post::where('slug', $alternativeSlug)->first();

       }
*/

       //vado a mettere nello slug il valore di $alternativeSlug
       $newPost->slug = $alternativeSlug;

       //salviamo il post
       $newPost->save();

       //facciamo un redirect alla lista dei post
       return redirect()->route('admin.posts.index');

   }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if(!$post){
            abort(404);
        }
        $category = Category::find($post->category_id);
        return view('admin.posts.show', compact('post', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {

        if(!$post){
            abort(404);
        }

        //prendo tutte le categorie
        $categories = Category::all();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        $request->validate([
            'title'=> 'required|max:250',
           'content'=> 'required|min:5',
       ],
       [    //messaggi di errore dei requisiti sopra
           'title.required' =>'Titolo deve essere valorizzato.',
           'title.max' =>'Hai superato i 250 caratteri.',
           'content.required' => 'Il contenuto deve essere compilato.',
           'content.min' => 'Minimo 5 caratteri.',
           'category_id.exists' => 'La categoria selezionata non esiste'
       ]);


        $postData = $request->all();

        $post->fill($postData);

        //$post->slug = Post::convertToSlug($post->title);

        $post->update();

        return redirect()->route('admin.posts.index', compact('post'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.posts.index', compact('post'));
    }
}
