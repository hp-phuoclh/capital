<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon\Carbon;

class RoleController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct() {
        // permission
        $this->middleware('permission:add roles')->only('create', 'store');
        $this->middleware('permission:edit roles')->only('edit', 'update');
        $this->middleware('permission:show roles')->only('show','index');
        $this->middleware('permission:delete roles')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get role
        $roles = Role::select('*')->where('name','<>','super-admin');
        if ($request->get("search_keyword")) {
            $roles = $roles->where(function ($query) use ($request){
                                $query->whereRaw("LOWER(name) like ?", '%'.$request->get("search_keyword").'%');
                            });
            // save request when search
            $request->flash();
        }
        // get permission
        $permissions = Permission::get();
        return view("role.index", ["roles" => $roles->paginate(),'permissions'=>$permissions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return view("role.create",['permissions'=>$permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);
            $role->save();
            $role->syncPermissions($request->permissions);

            DB::commit();
            return redirect()->back()->with(['success' => __('Created success!')]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => __('Error!')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $role_id = $request->role_id;
        $permissions = $request->permissions;

        // find role
        $role = Role::find($role_id);
        if(!$role) {
            $result = [
                'status' => 'error',
                'message' => __('The role does not exist'),
            ];
            return json_encode($result);
        }
        // syncPermissions
        $role->syncPermissions($permissions);

        // return
        $result = [
            'status' => 'success',
            'message' => __('Update successfully!'),
        ];
		return json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
