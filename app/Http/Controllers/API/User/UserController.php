<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\Base\ApiController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->SuccessResponse($users);
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
        $rules = [
                'name'=>'required',
                'email'=>'required|email|unique:users',
                'password'=>'required|min:6|confirmed',
                ];

        $this->validate($request,$rules);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);


        return $this->SuccessResponse($user);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // $user = User::findOrFail($id);
        return $this->SuccessResponse($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
         // $user = User::findOrFail($id);
         $rules = [
                'email'=>'email|unique:users,email,'.$user->id,
                'password'=>'min:6|confirmed',
                'admin'=>'in:,'.User::ADMIN_USER.','.User::REGULAR_USER,
                ];

        if($request->has('name')){
            $user->name = $request->name;

        }
        if($request->has('password')){
            $user->password = $request->password;

        }
        if($request->has('email') and $user->email !=$request->email){
            $user->email = $request->email;
            $user->verified = User::UNVERIFIED_USER;
            $user->verified = User::generateVerificationCode();
        }
        if($request->has('admin')){
            if(!$user->isVerified()){
                return $this->ErrorResponse("only verified user can change admin field",409);
            
            }
            $user->admin = $request->admin;
        }
        if(!$user->isDirty()){
           return $this->ErrorResponse("Specify value to update",422);
            
            }
        
        $user->save();
       return $this->SuccessResponse($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
         // $user = User::findOrFail($id);
         $user->delete();
         return $this->SuccessResponse($user);
        
    }
}
