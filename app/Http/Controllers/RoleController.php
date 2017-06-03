<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Validator;
use Sentinel;
use Route;
class RoleController extends Controller
{
      protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'slug' => 'required|max:35|min:2|string',
            'name' => 'required|max:35|min:2|string',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $roles = Role::all();

        if ($request->is('api/*')) {
               return $roles;
         } 
        return View('backEnd.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {    $roles = Role::get()->pluck('name', 'id');
        return View('backEnd.roles.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request){
        if ($this->validator($request)->fails()) {
            return redirect()->back()
                        ->withErrors($this->validator($request))
                        ->withInput();
        }
         
        Role::create($request->all());
        Session::flash('message', 'Success! Role is created successfully.');
        Session::flash('status', 'success');

        return redirect('role');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        return View('backEnd.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return View('backEnd.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        if ($this->validator($request)->fails()) {
            return redirect()->back()
                        ->withErrors($this->validator($request))
                        ->withInput();
        }
        
        $role = Role::findOrFail($id);
        $role->update($request->all());

        Session::flash('message', 'Success! Role is updated successfully.');
        Session::flash('status', 'success');

        return redirect('role');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->delete();

        Session::flash('message', 'Success! Role is deleted successfully.');
        Session::flash('status', 'success');

        return redirect('role');
    }

    public function permissions($id){
        $role = Sentinel::findRoleById($id);
        
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
        return View('backEnd.roles.permissions', compact('role', 'actions'));
    }

    public function save($id, Request $request){
        $role = Sentinel::findRoleById($id);
        
        $role->permissions = [];
        if($request->permissions){
            foreach ($request->permissions as $permission) {
                if(explode('.', $permission)[1] == 'create'){
                    $role->addPermission($permission);
                    $role->addPermission(explode('.', $permission)[0].".store");                
                }
                else if(explode('.', $permission)[1] == 'edit'){
                    $role->addPermission($permission);
                    $role->addPermission(explode('.', $permission)[0].".update");                
                }
                else{
                    $role->addPermission($permission);
                }            
            }  
        }
        
        $role->save();
        
        Session::flash('message', 'Success! Permissions are stored successfully.');
        Session::flash('status', 'success');
        return redirect('role');
    }
    

}
