<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Roles;
use App\UserRoles;
use Validator;
use DB;
use Illuminate\Support\Facades\Hash;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with('UserRoles.Roles')->where('is_deleted',0)->get();
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
            'username' => 'required',
            'password' => 'required',
            'email' => 'required',
            'role_id' => 'required'
        ]);
        if ($validation->fails()) {
            return response(['success' => false, 'data' => 'Validation Failed', 'errors' => $validation->errors()]);
        }

        DB::beginTransaction(); // PDO Connection
        try{
            $users = new User;
            $users->username = $request->username;
            $users->email = $request->email;
            $users->password = Hash::make($request->password);
            $users->user_img = 'user.png';
            $users->save();

            $user_roles = new UserRoles;
            $user_roles->user_id = $users->id;
            $user_roles->role_id = $request->role_id;
            $user_roles->save();

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
                'data' => 'User Updated' 
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
        return User::with('UserRoles.Roles')->find($id);
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
            'username' => 'required',
            'email' => 'required',
            'role_id' => 'required'
        ]);
        if ($validation->fails()) {
            return response(['success' => false, 'data' => 'Validation Failed', 'errors' => $validation->errors()]);
        }

        DB::beginTransaction(); // PDO Connection
        try{
            $users = User::find($id);
            $users->username = $request->username;
            $users->email = $request->email;
            $users->user_img = 'user.png';
            $users->save();

            $user_roles = UserRoles::where('user_id',$id)->first();
            $user_roles->role_id = $request->role_id;
            $user_roles->save();

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
                'data' => 'User Updated' 
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
        $delete_user = User::find($id);
        $delete_user->is_deleted = 1;
        $delete_user->save();
    }

    public function roles(){
        return Roles::get();
    }
}
