<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\PhoneNumber;

class UserController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct() {
        // permission
        $this->middleware('permission:add users')->only('create', 'store');
        $this->middleware('permission:edit users')->only('edit', 'update');
        $this->middleware('permission:show users')->only('show','index');
        $this->middleware('permission:delete users')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get client user
        $users = User::select('*');
        if ($request->get("search_keyword")) {
            $users = $users->where(function ($query) use ($request){
                            $query->whereRaw("LOWER(name) like ?", '%'.$request->get("search_keyword").'%')
                            ->orWhereRaw("LOWER(email) like ?", '%'.$request->get("search_keyword").'%');
                        });
            // save request when search
            $request->flash();
        }
        return view("user.index", ["users" => $users->paginate()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        return view("user.edit", ["user" => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => ['required', 'unique:users,phone', new PhoneNumber],
            'email' => 'email|required|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $request->merge(["password"=>Hash::make( $request->password)]);

        $user = User::create($request->all());

        return redirect()->route('users.create')
            ->with(['success' => 'Create success!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view("user.view", ["user" => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view("user.edit", ["user" => $user]);
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
        $user = User::findOrFail($id);

        $validate = [
            'name' => 'required',
            'email' => 'email|required|unique:users,email,'.$user->id,
        ];

        $request->validate($validate);

        if ($request->password) {
            $validate['password'] = 'required|string|min:8|confirmed';
        }
        $request->validate($validate);

        if ($request->password) {
            $user->password = Hash::make( $request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->save();

        return redirect()->route('users.edit', ['user' => $user->id])
            ->with(['success' => __('Update successfully!')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back();
    }
}
