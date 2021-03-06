<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use View;
use App\User;

use Auth;

class UserController extends Controller
{

	public function __construct(){		
		$this->middleware('auth');
	  
	}
	
	
	
	public function index(Request $request)
   {

		
		$q = $request->get('q');
        $users = User::where('user_type', '=',0)
		//->where('name', 'LIKE', '%'.$q.'%' or 'name', 'LIKE', '%'.$q.'%')
		//->orWhere('$q',function ($query) {
             //   $query->where('name', 'LIKE', '%'.$q.'%')
             //         ->orWhere('name', 'LIKE', '%'.$q.'%');
           // })
           
			//->Orwhere('name', 'LIKE', '%'.$q.'%')
			// ->where('user_type', '=',0)
            ->orderBy('name')->paginate(9);
        return View::make('user.index', compact('users', 'q'));
		//return View::make('user.index');
   }
   /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
   public function create()
   {
	 if(empty(Auth::check())){
		   return Redirect::to('/');
	   }
     return View::make('user.create');
   }
   /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
   public function store(Request $request)
    {
		// validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'name'       => 'required',
            'email'      => 'required|email|unique:user,email',
			'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('user/create')
                ->withErrors($validator)
                ->withInput($request->except('password'));
        } else {
            // store
            $user = new User;
            $user->name       = $request->get('name');
            $user->email      = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->user_type = 0;
            $user->save();

            // redirect
            //Session::flash('message', 'Successfully created nerd!');
			$request->session()->flash('alert-success', 'User was successful added!');
            return Redirect::to('user/index');
        }
   }
   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
   public function show($id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
   public function edit($id)
   {
       if(empty(Auth::check())){
		   return Redirect::to('/');
	   }
	   $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
   }
   /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @return Response
    */
   public function update(Request $request,$id)
   {
		$user = User::findOrFail($id);
		$rules = array(
            'name'       => 'required',
            'email'      => 'required|email|unique:user,email,'.$user->id,
            'password' => 'required'
        );
		$this->validate($request, $rules);
        $user->update($request->all());
        //\Flash::success('User updated successfully.');
		$request->session()->flash('alert-success', 'User was updated successfully.');
        return redirect()->route('user.index');
   }
   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return Response
    */
   public function destroy(Request $request,$id)
   {
		User::find($id)->delete();
		$request->session()->flash('alert-success', 'User was deleted successfully.');
        return redirect()->route('user.index');
   }
}
