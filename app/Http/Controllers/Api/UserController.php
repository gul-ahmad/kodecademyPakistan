<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;








class UserController extends Controller
{


     public function __construct()
    {
        $this->middleware('auth:api');
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //return ['message'=>'I am here in the Index Page'];
       $usersList =User::all();

       return $usersList;
    }
  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // return ['message'=>'I am here in the store'];
       
      //  $input = $request->all();
        $this->validate($request, [ 
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

      //  dd($input);



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
    public function profile()
    {
        //return currently authenticated uwer
        return auth('api')->user();
       // dd($abc);
    }
    public function updateprofile(Request $request)
    {
        //return currently authenticated uwer

        
        $user = auth('api')->user();
        $this->validate($request, [ 
            'name' => ['required', 'string', 'max:255'],
           // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => ['sometimes', 'string', 'min:8'],
        ]);
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        $user->update($request->all());
        return ['message' => "Success"];


       
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
        $user =User::findOrFail($id);  
        $this->validate($request, [ 
            'name' => ['required', 'string', 'max:255'],
           // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => ['sometimes', 'string', 'min:8'],
        ]);
        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   //  return ['message'=>'I am here in the store'];
        $user =User::findOrFail($id);  
          $user->delete();
    }
}
