<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TblTags;
use Auth;
use Validator;
use DB;
class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = TblTags::where('is_deleted',0)->get();
        
        return $tags;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'tag' => 'required|unique:tbl_tags'
            
        ]);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'data' => 'Validation Failed', 'errors' => $validation->errors()]);
        }
      
        DB::beginTransaction(); // PDO Connection
        try{
            $tag = new TblTags;
            $tag->tag = $request->tag;
            $tag->save();

        }catch(\ValidationException $e) {
            \Log::critical($e); 
            DB::rollback(); 
            if($request->ajax()) {
                return response([
                    'success' => false,
                    'data' => $e
                ]);
            }
            
            
        }catch(\Exception $e) {
            \Log::critical($e); 
            DB::rollback(); 
            if($request->ajax()) {
                return response([
                    'success' => false,
                    'data' => $e
                ]);
            }       
        }
        DB::commit();

        if($request->ajax()) {
            return response([
                'success' => true,
                'data' => 'Tag Added' 
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return TblTags::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[            
            'tag' => 'required|unique:tbl_tags'
            
        ]);
        if ($validation->fails()) {
            return response(['success' => false, 'data' => 'Validation Failed', 'errors' => $validation->errors()]);
            return redirect('/tags')
                ->withErrors($validation)
                ->withInput();
        }

        DB::beginTransaction(); // PDO Connection
        try{
            $tag = TblTags::find($id);
            $tag->tag = $request->tag;
            $tag->save();

        }catch(\ValidationException $e) {
            \Log::critical($e); 
            DB::rollback(); 
            if($request->ajax()) {
                return response([
                    'success' => false,
                    'data' => $e
                ]);
            }
            return redirect('/tags')
            ->withErrors($e)
            ->withInput();
            
        }catch(\Exception $e) {
            \Log::critical($e);
            DB::rollback();
            if($request->ajax()) {
                return response([
                    'success' => false,
                    'data' => $e
                ]);
            }
            return redirect('/tags')
            ->withErrors($e)
            ->withInput();
            
        }
        DB::commit();

        if($request->ajax()) {
            return response([
                'success' => true,
                'data' => 'Tag Updated' 
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete_tag = TblTags::find($id);
        $delete_tag->is_deleted = 1;
        $delete_tag->save();
    }
}
