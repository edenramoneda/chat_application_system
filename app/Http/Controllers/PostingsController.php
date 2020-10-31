<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\TblPostings;
use App\TblCategories;
use App\TblStatus;
use App\TblImage;
use App\TblPostingsTags;
use Validator;
use DB;
use Auth;

class PostingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $postings = TblPostings::
        with('Categories')
        ->with('Status')
        ->with('Author')->where('is_deleted', 0)->get();
        
        return json_encode($postings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = TblCategories::all();
        $status = TblStatus::all();
        $tags = TblTags::all();
        
        // if(!\Gate::allows('isEditorInChief') && !\Gate::allows('isAuthor')){
        //     abort(403,"You are not allowed to performed this action");
        // }
        // return view('/postings/addPost',['categories' => $categories,'status' => $status,'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[            
            'category_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'status_id' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 403);
        }else {
            DB::beginTransaction();
            $id = 0;

            try{
               // $params = $request->all();
                // $posts = new TblPostings;
                // $posts->category_id = $request->category_id;
                // $posts->title = $request->title;
                // $posts->content = $request->content;
                // $posts->status_id = $request->status_id;
                // $posts->img = 'nocamera.png';
                // $posts->posted_by = Auth::user()->id;
                // $posts->save();
                // $id = $posts->id;

                $image = $request->file('img');
           
                $posts = new TblPostings;
                $posts->category_id = $request->category_id;
                $posts->title = $request->title;
                $posts->content = $request->content;
                $posts->status_id = $request->status_id;
                
                if($image){
                    $extension_image = $image->getClientOriginalExtension();
                    Storage::disk('public')->put('uploads/'.$image->getFilename().'.'.$extension_image, File::get($image));
                    $posts->img = $image->getFilename().'.'.$extension_image;  
                }else{
                    $posts->img = 'nocamera.png';
                }
                $posts->posted_by = Auth::user()->id;
                $posts->save();


                if ($request->has('tag_id')) {
                    $getTags = json_decode($request->tag_id);
                    foreach($getTags as $key => $tags){
                        $postings_tags = new TblPostingsTags;
                        $postings_tags->posting_id = $posts->id;
                        $postings_tags->tag_id = $tags;
                        $postings_tags->save();  
                    }   
                }
                
                DB::commit();

                return response([
                    'success' => true,
                    'id' => $id
                ]);
            }catch(Exception $e){
                \Log::critical($e); 
                DB::rollback(); 
                
                return response([
                    'success' => false,
                    'message' => 'Error Occured'
                ]);

            }
            
            return response([
                'success' => false,
                'message' => 'Error Occured'
            ]);     
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TblPostings  $tblPostings
     * @return \Illuminate\Http\Response
     */
    public function show($tblPostings)
    {
        $posts = TblPostings::with('Categories')->with('PostTags')->where([['is_deleted','=',0],['id','=', $tblPostings]])->first();
        return new PostingsResource($posts);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TblPostings  $tblPostings
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $postings = TblPostings::with('Categories')->with('Status')->with('PostTags.Tags')->find($id);
        // $categories = TblCategories::all()->where('id','<>',$postings->category_id);
        // $status = TblStatus::all()->where('id','<>',$postings->status_id);
        // $PostingsTags = TblPostingsTags::where('posting_id',$postings->id)->get();
        // $tags = TblTags::all();

        return json_encode($postings);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TblPostings  $tblPostings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[            
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'content' => 'required|string',
            'status_id' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 403);
        }else {
            DB::beginTransaction();

            try{

                $image = $request->file('img');
           
                $posts = TblPostings::find($id);
                $posts->category_id = $request->category_id;
                $posts->title = $request->title;
                $posts->content = $request->content;
                $posts->status_id = $request->status_id;

                if($image){
                    $extension_image = $image->getClientOriginalExtension();
                    Storage::disk('public')->put('uploads/'.$image->getFilename().'.'.$extension_image, File::get($image));
                    $posts->img = $image->getFilename().'.'.$extension_image;  
                }
                
                $posts->posted_by = Auth::user()->id;
                $posts->save();

                if ($request->has('tag_id')) {
                    $getTags = json_decode($request->tag_id);

                    foreach($getTags as $key => $tags){
                        // TblPostings::where('posting_id', $id)
                        // ->update(['']);
                        // $postings_tags->posting_id = $posts->id;
                        // $postings_tags->tag_id = $tags;
                        // $postings_tags->save();  
                        TblPostingsTags::updateOrCreate(
                            ['posting_id' =>  $posts->id,'tag_id' => $tags]
                        );
                    }   
                }
                
                DB::commit();

                return response([
                    'success' => true,
                    'message' => 'Success'
                ]);
            }catch(Exception $e){
                \Log::critical($e); 
                DB::rollback(); 
                
                return response([
                    'success' => false,
                    'message' => 'Error Occured'
                ]);

            }
            
            return response([
                'success' => false,
                'message' => 'Error Occured'
            ]);       
        }
    }


    public function showPhoto($id){
         $img = TblPostings::find($id);
        return response()->file('uploads/' . $img->img);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TblPostings  $tblPostings
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $delete_post = TblPostings::find($id);
        $delete_post->is_deleted = 1;
        $delete_post->save();     
       
        // activity()
        // ->causedBy(Auth::user()->id)
        // ->performedOn($delete_post)
        // ->withProperties($delete_post->attributesToArray())
        // ->log('softdeleted');
    }
    
    public function UploadImage(Request $request){
        $images = new TblImage;

        $validation = Validator::make($request->all(),[            
            'upload' => 'image|mimes:jpg,jpeg,png|required'
        ]);
      
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 403);
        }
        DB::beginTransaction(); // PDO Connection
        try{
            $image = $request->file('upload');
            if($image){
                $extension_image = $image->getClientOriginalExtension();
                Storage::disk('public')->put('uploads/'.$image->getFilename().'.'.$extension_image, File::get($image));
                $images->images = $image->getFilename().'.'.$extension_image;  
            }
            $images->save();

            DB::commit();
            
        }catch(Exception $e){
            \Log::critical($e); 
            DB::rollback(); 
            
            return response([
                'success' => false,
                'message' => $e
            ]);

        }
    }

    public function Status(){
        $status = TblStatus::get();
        
        return json_encode($status);
    }

    public function viewImg($id){
        $img = TblPostings::find($id);
       
      // return Image::make(asset($img->img))->response();

      return "/uploads/" . $img->img . "";
    }
}
