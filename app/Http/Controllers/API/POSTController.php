<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class POSTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts']= Post::all();
        return response()->json([
            'status' => true,
            'message' => 'All post data',
            'data'=> $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate =Validator::make($request->all(),[
            "title"=> "required",
            "description"=> "required",
            'image' => 'required|mimes:png,jpg,jpeg,gif',
        ]);
        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()->all()
            ],401);
        }
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $image->move(public_path('/uploads/'),$imageName);
             $post = Post::create([
                'title' => $request->title,
                'description'=> $request->description,
                'image'=>$imageName,
             ]);
             return response()->json([
                'status' => true,
                'message' => 'Post created successfully',
                'post' => $post,
            ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['Post'] =Post::select(
            'id',
            'title',
            'description',
            'image',
        )->where(['id'=> $id])->get();
        return response()->json([
            'status' => true,
            'message' => 'Your single Post',
            'data' => $data,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate =Validator::make($request->all(),[
            "title"=> "required",
            "description"=> "required",
            'image' => 'required|mimes:png,jpg,jpeg,gif',
        ]);
        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()->all()
            ],401);
            
        }
        $postimage=Post::select('id','image')->where(['id'=> $id])->get();
        if(!empty($request->postimage)){
        File::delete(public_path("/uploads/".$postimage->image));
        }
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $image->move(public_path('/uploads/'),$imageName);
             $post = Post::where(['id'=>$id])->update([
                'title' => $request->title,
                'description'=> $request->description,
                'image'=>$imageName,
             ]);
             return response()->json([
                'status' => true,
                'message' => 'Post updated successfully',
                'post' => $post,
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delteImage = Post::select('image')->where('id',$id)->get();
        File::delete(public_path('/uploads/'.$delteImage->image));
        $post =Post::where('id',$id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'Your post has been removed.',
            'post' => $post,
        ],200);
    }
}
