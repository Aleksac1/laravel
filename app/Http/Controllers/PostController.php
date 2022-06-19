<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\String\Slugger\SluggerInterface;




class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* listamo sve postove opadajuce*/ 
    public function allposts(){
        $posts=Post::all();
        $posts = POST::orderBy('published_at', 'DESC')->paginate(15,"*","page");
        $users=User::all();
        return view('allposts',compact('posts','users'));
    }
/*rastuce */ 
    public function allusers(){
        $users=User::all();
        $users=User::orderBy('name', 'ASC')->paginate(2,"*","page");
        return view('allusers',compact('users'));
    }



    public function userPosts($id){
        $user=User::findOrFail($id);
        $userPosts=Post::all();
        $userPosts=Post::where("user_id","=","$id")->paginate(4,"*","page");
        return view('userPosts',compact('userPosts','user'));
    }
/*user da je jednak id --u koje je proslijedjen */
    public function myPosts(){
        $user=auth()->user()->id;
        $myposts=Post::all();
        $myposts=Post::where("user_id","=","$user")->paginate(4,"*","page");
        return view('myposts',compact('myposts'));
    }

    public function myProfile(){
        $user=auth()->user();
        return view('myprofile',compact('user'));

    }
   
    public function index()
    {
        $posts =POST::all();
        
        $posts = POST::orderBy('published_at', 'DESC')->paginate(4,"*","page");
        $users=DB::select(DB::raw("SELECT * FROM users"));
        
        
        return view('index',compact('posts',"users"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $id=auth()->user()->id;
        $user=User::findOrFail($id);
        
        
 /*validacija podataja iz forme requierd */ 
        $request->validate([
            "title"=>"required",
            "short_description"=>"required",
            "content"=>"required",
            
        ]);
        
        /*ako ima fajl to jest sliku validejta ga */
        if ($request->hasFile('picture')) {


            $request->validate([
                "picture"=>"mimes:png,jpeg,bmp"]);
                $request->picture->store('slike', 'public');/*stora sliek u folders slike public folder*/ 


                
    /*pravi post request promjenjliva ili niz koja mi sacuva podatke iz forme*/ 
        $post=Post::create([
            'title'=> $request->title,
            
            "short_description"=> $request->short_description,
            'content'=> $request->content,
            "user_id"=> auth()->id(),
            'picture'=> $request->picture->hashName()
            
        ]);
       
        
        $user->postFunction()->save($post);/* cuva podatke po juzeru post func iz modela */ 
        
    }
    
    return redirect('/posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    /* jedan red */ 
    public function show($id)
    {
        $post=Post::findOrFail($id);
        $user=User::findOrFail($post->user_id);
        return view('post',compact('post','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $post=Post::findOrFail($id);

        return view('Posts.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    /* dugme za save*/ 
    public function update($id,UpdatePostRequest $request)
    {
        $request->validate([
            "title"=>"required",
            "short_description"=>"required",
            "content"=>"required"
        ]);
     /*vidi ima li slike da se mijenja ako ne ide dolje*/ 
        if ($request->hasFile('picture')) {


            $request->validate([
                "picture"=>"mimes:png,jpeg,bmp"]);
                $request->picture->store('slike', 'public');

                /* nadje post izmijeni sta treba*/ 
                $post=Post::findOrFail($id);
                $post->title=$request->title;
                $post->short_description=$request->short_description;
                $post->content=$request->content;
                
                $post->picture=$request->picture->hashName();
                
                $post->update();



        return redirect('/posts');
    }
    else{
                $post=Post::findOrFail($id);
                $post->title=$request->title;
                $post->short_description=$request->short_description;
                $post->content=$request->content;
                $post->update();
                return redirect('/posts');
    }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $postt=Post::findOrFail($id);
        @unlink(asset('storage/slike/'.$postt->picture)); 
        $postt->delete();
        return redirect('/posts');
    }
}
