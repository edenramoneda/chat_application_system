<?php

namespace App\Http\Controllers;

use App\TblCategories;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DB;
class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $categories = TblCategories::where('is_deleted',0)->get();
        
        return json_encode($categories);
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
            'category_title' => 'required|unique:tbl_categories'
            
        ]);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'data' => 'Validation Failed', 'errors' => $validation->errors()]);
        }
      
            DB::beginTransaction(); // PDO Connection
            try{
                $categories = new TblCategories;
                $categories->category_title = $request->category_title;
                $categories->save();

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
                    'data' => 'Category Added' 
                ]);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TblCategories  $tblCategories
     * @return \Illuminate\Http\Response
     */
    public function show(TblCategories $tblCategories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TblCategories  $tblCategories
     * @return \Illuminate\Http\Response
     */
    public function edit(TblCategories $tblCategories,$id)
    {
        return TblCategories::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TblCategories  $tblCategories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validation = Validator::make($request->all(),[            
            'category_title' => 'required|unique:tbl_categories'
            
        ]);
        if ($validation->fails()) {
            return response(['success' => false, 'data' => 'Validation Failed', 'errors' => $validation->errors()]);
            return redirect('/categories')
                ->withErrors($validation)
                ->withInput();
        }

        DB::beginTransaction(); // PDO Connection
        try{
            $categories = TblCategories::find($id);
            $categories->category_title = $request->category_title;
            $categories->save();

        }catch(\ValidationException $e) {
            \Log::critical($e); 
            DB::rollback(); 
            if($request->ajax()) {
                return response([
                    'success' => false,
                    'data' => $e
                ]);
            }
            return redirect('/categories')
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
            return redirect('/categories')
            ->withErrors($e)
            ->withInput();
            
        }
        DB::commit();

        if($request->ajax()) {
            return response([
                'success' => true,
                'data' => 'Category Updated' 
            ]);
        }
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TblCategories  $tblCategories
     * @return \Illuminate\Http\Response
     */
    public function destroy(TblCategories $tblCategories,$id)
    {
        $delete_category = TblCategories::find($id);
        $delete_category->is_deleted = 1;
        $delete_category->save();

        // activity()
        // ->causedBy(Auth::user()->id)
        // ->performedOn($delete_category)
        // ->withProperties($delete_category->attributesToArray())
        // ->log('softdeleted');
    }
}
