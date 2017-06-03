<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;
use Validator;
use Session;
use Auth;
use Route;
use Sentinel;
use Activation;
use DB;
use Hash;
use Mail;
use Carbon\Carbon;


class UserController extends Controller
{
    public function __construct()
    {
      //$this->middleware('auth')->except('orders');
      // $this->middleware('auth');
    }
    protected function validator(Request $request,$id='')
    {
        return Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:35|string',
            'last_name' => 'required|min:2|max:35|string',            
            'email' => Sentinel::inRole('Admin')?'required|email|min:3|max:50|string':(Sentinel::check()?'required|email|min:3|max:50|string|unique:users,email,'.$id:'required|email|min:3|max:50|unique:users|string'),
            'password' => 'min:6|max:50|confirmed',
            //'gender' => 'required',
            'role' => 'required',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
         $type = $request->type;
         $users= User::all();
         if ($type) {
          $role = Sentinel::findRoleBySlug( $type);
          $users = $role->users()->get();

         }
        
        return View('backEnd.users.index', compact('users')); 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
       
        $roles = Role::get()->pluck('name', 'id');
        return View('backEnd.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
       
        if ($this->validator($request,Sentinel::getUser()->id)->fails()) {
            
                return redirect()->back()
                        ->withErrors($this->validator($request))
                        ->withInput();
        }
         //create user
         $user = Sentinel::register($request->all());
         //activate user
         $activation = Activation::create($user);
         $activation = Activation::complete($user, $activation->code);
         //add role
         $user->roles()->sync([$request->role]);

        Session::flash('message', 'Success! User is created successfully.');
        Session::flash('status', 'success');
        
        return redirect()->route('user.index');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
         $user = User::findOrFail($id);
         $type = $user->roles()->first();
         if ($request->is('api/*')) {
            $user= User::where('id',$id)->with('activations','roles')->get();
            return response()->json(compact('user'));
        }
        return View('backEnd.users.show', compact('user','type')); 
    }
    public function accountFrontEnd(Request $request,$id)
    {   
        $user=Sentinel::getUser();
         if ($user->inRole('admin')) {
           $user = User::findOrFail($id);
           return view('frontend.userAcount',compact('user'));
         }
       
        return view('frontend.userAcount',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {   
        $user = User::find($id);
        $roles = Role::get()->pluck('name', 'id');
        return View('backEnd.users.edit', compact('user', 'roles'));
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
        
      
        $update_user = Validator::make($request->all(), [
            'first_name' => 'min:2|max:35|string',
            'last_name' => 'min:2|max:35|string',            
            'email' => Sentinel::inRole('Admin')?'required|email|min:3|max:50|string':(Sentinel::check()?'required|email|min:3|max:50|string|unique:users,email,'.$id:'required|email|min:3|max:50|unique:users|string'),
        ]);

        if ($update_user->fails()) {
            return redirect()->back()
                        ->withErrors($update_user)
                        ->withInput();
        }

        $user = User::find($id);
        if ($user) {

              if($request->first_name){
              $user->first_name=$request->first_name;
              }
              if($request->last_name){
              $user->last_name=$request->last_name;
              }
              if($request->email){
              $user->email=$request->email;
              }
              if($request->new_password && $request->new_password_confirmation ){
                if ($request->new_password == $request->new_password_confirmation ){
                     $user->password=bcrypt($request->new_password);
                 }else{
                   Session::flash('message', 'Your old password is incorrect.');
                   Session::flash('status', 'error');
                  return redirect()->back()->withErrors(['old_password', 'your old password is incorrect']);
                 }
              }
              $user->update();
            if ($request->role) {
              $user->roles()->sync([$request->role]);
            }
            Session::flash('message', 'Success! User is updated successfully.');
            Session::flash('status', 'success');
            
        } 


      return redirect()->back();
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        Session::flash('message', 'Success! User is deleted successfully.');
        Session::flash('status', 'success'); 

        return redirect()->route('user.index');
    }

    public function permissions($id)
    {
        $user = Sentinel::findById($id);
        $routes = Route::getRoutes();


        //Api Route
        // $api = app('api.router');
        // /** @var $api \Dingo\Api\Routing\Router */
        // $routeCollector = $api->getRoutes(config('api.version'));
        // /** @var $routeCollector \FastRoute\RouteCollector */
        // $api_route = $routeCollector->getRoutes();


        $actions = [];
        foreach ($routes as $route) {
            if ($route->getName() != "" && !substr_count($route->getName(), 'payment')) {
                $actions[] = $route->getName();
            }            
        }
        
        //remove store option
        $input = preg_quote("store", '~');
        $var = preg_grep('~' . $input . '~', $actions);
        $actions = array_values(array_diff($actions, $var));

        //remove update option
        $input = preg_quote("update", '~');
        $var = preg_grep('~' . $input . '~', $actions);
        $actions = array_values(array_diff($actions, $var));

        //Api all names
        // foreach ($api_route as $route) {
        //     if ($route->getName() != "" && !substr_count($route->getName(), 'payment')) {
        //         $actions[] = $route->getName();
        //     }            
        // }
        
        $var = [];
        $i = 0;
        foreach ($actions as $action) {

            $input = preg_quote(explode('.', $action )[0].".", '~');
            $var[$i] = preg_grep('~' . $input . '~', $actions);
            $actions = array_values(array_diff($actions, $var[$i]));
            $i += 1;
        }

        $actions = array_filter($var);
        // dd (array_filter($actions));

        return View('backEnd.users.permissions', compact('user', 'actions'));
    }

    public function save($id, Request $request)
    {
        //return $request->permissions;
        $user = Sentinel::findById($id);
        $user->permissions = [];
        if($request->permissions){
            foreach ($request->permissions as $permission) {
                if(explode('.', $permission)[1] == 'create'){
                    $user->addPermission($permission);
                    $user->addPermission(explode('.', $permission)[0].".store");                
                }
                else if(explode('.', $permission)[1] == 'edit'){
                    $user->addPermission($permission);
                    $user->addPermission(explode('.', $permission)[0].".update");                
                }
                else{
                    $user->addPermission($permission);
                }            
            }  
        }
        
        $user->save();

        Session::flash('message', 'Success! Permissions are stored successfully.');
        Session::flash('status', 'success');

        return redirect()->route('user.index');
    }

    public function activate(Request $request,$id)
    {
        $user = Sentinel::findById($id);

        $activation = Activation::completed($user);

        if($activation){
            Session::flash('message', 'Warning! The user is already activated.');
            Session::flash('status', 'warning');
             
            return redirect('user');
        }
        $activation = Activation::create($user);
        $activation = Activation::complete($user, $activation->code);
       
        Session::flash('message', 'Success! The user is activated successfully.');
        Session::flash('status', 'success');
            
        $role = $user->roles()->first()->name;
        
        return redirect()->route('user.index');
    }

    public function deactivate(Request $request,$id){

        $user = Sentinel::findById($id);
        Activation::remove($user);
        
        Session::flash('message', 'Success! The user is deactivated successfully.');
        Session::flash('status', 'success');

        return redirect()->route('user.index');
    }
    public function ajax_all(Request $request){
        if ($request->action=='delete') {
           foreach ($request->all_id as $id) {
             $user = User::findOrFail($id);
             if ($user->deleted_at == null){$user->delete();} 
            }
            Session::flash('message', 'Success! Users are deleted successfully.');
            Session::flash('status', 'success'); 
            return response()->json(['success' => true, 'status' => 'Sucesfully Deleted']);
        }
        if ($request->action=='deactivate') {
           foreach ($request->all_id as $id) {
             $user = User::findOrFail($id);
             $activation = Activation::completed($user);
             if ($activation){Activation::remove($user);} 
            }
            Session::flash('message', 'Success! Users are deactivate successfully.');
            Session::flash('status', 'success'); 
            return response()->json(['success' => true, 'status' => 'Sucesfully deactivate']);
        }
        if ($request->action=='activate') {
           foreach ($request->all_id as $id) {
             $user = User::findOrFail($id);
             $activation = Activation::completed($user);
             if ($activation==''){
                $activation = Activation::create($user);
                $activation = Activation::complete($user, $activation->code);
                } 
            }
            Session::flash('message', 'Success! Users are Activated successfully.');
            Session::flash('status', 'success'); 
            return response()->json(['success' => true, 'status' => 'Sucesfully Activated']);
        }
    }
   




}
