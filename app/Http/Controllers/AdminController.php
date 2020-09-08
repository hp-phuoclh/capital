<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Spatie\Permission\Models\Role;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct() {
        // permission
        $this->middleware('permission:add admins')->only('create', 'store');
        $this->middleware('permission:edit admins')->only('edit', 'update');
        $this->middleware('permission:show admins')->only('show','index');
        $this->middleware('permission:delete admins')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get client admin
        $admins = Admin::select('*');
        if ($request->get("search_keyword")) {
            $admins = $admins->where(function ($query) use ($request){
                            $query->whereRaw("LOWER(name) like ?", '%'.$request->get("search_keyword").'%')
                            ->orWhereRaw("LOWER(email) like ?", '%'.$request->get("search_keyword").'%');
                        });
            // save request when search
            $request->flash();
        }
        return view("admin.index", ["admins" => $admins->paginate()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name','<>','super-admin')->get();
        return view("admin.create",["roles" => $roles]);
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
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'role' => 'required',
        ]);
        // except super-admin
        if($request->input('role') == 'super-admin') {
            return redirect()->back()->with(['error' => __('Error!')]);
        }

        DB::beginTransaction();
        try {
            // create admin
            $admin = new Admin();
            $admin->name = $request->input('name');
            $admin->email = $request->input('email');
            $admin->password = Hash::make(Str::random(32));
            $admin->save();

            // asign role
            $admin->assignRole($request->input('role'));

            // generate token
            $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($admin);
            // create token admin
            $reset_password = new PasswordReset();
            $reset_password->email = $request->input('email');
            $reset_password->token = $token;
            $reset_password->created_at = Carbon::now();
            $reset_password->save();

            $admin->sendAdminInviteMemberNotification($reset_password);

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
        $roles = Role::where('name','<>','super-admin')->get();
        $admin = Admin::findOrFail($id);
        if($admin->hasRole('super-admin')) {
            return redirect()->back()->with(['error' => __('Error!')]);
        }
        return view("admin.edit", ["admin" => $admin,"roles" => $roles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        // if super-admin except
        if($admin->hasRole('super-admin')) {
            return redirect()->back()->with(['error' => __('Error!')]);
        }

        $validate = [
            'name' => 'required',
            'email' => 'email|required|unique:admins,email,'.$admin->id,
            'roles.*' => 'required',
        ];
        $request->validate($validate);

        // except super-admin
        if($request->input('roles')[0] == 'super-admin') {
            return redirect()->back()->with(['error' => __('Error!')]);
        }

        if ($request->password) {
            $validate['password'] = 'required|string|min:8|confirmed';
        }
        $request->validate($validate);

        if ($request->password) {
            $admin->password = Hash::make( $request->password);
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        // assignRole
        $admin->syncRoles($request->roles);

        return redirect()->route('admins.edit', ['admin' => $admin->id])
            ->with(['success' => __('Update successfully!')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->back()->with(['success' => __('Delete successfully!')]);;
    }
}
