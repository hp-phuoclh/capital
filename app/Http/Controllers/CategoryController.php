<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct() {
        // permission
        $this->middleware('permission:add categories')->only('create', 'store');
        $this->middleware('permission:edit categories')->only('edit', 'update');
        $this->middleware('permission:show categories')->only('show','index');
        $this->middleware('permission:delete categories')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get client category
        $categories = Category::select("*");
        if ($request->get("search_keyword")) {
            $categories = $categories->where(function ($query) use ($request){
                            $query->whereRaw("LOWER(name) like ?", '%'.$request->get("search_keyword").'%');
                        });
            // save request when search
            $request->flash();
        }
        $categories = $categories->orderBy('order');
        return view("category.index", ["categories" => $categories->paginate()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = new Category;
        return view("category.edit", ["category" => $category]);
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
            'order' => 'nullable|numeric',
        ]);
        
        $request->merge(["order"=> $request->order ?: 0]);
        $category = Category::create($request->all());

        return redirect()->route('categories.create')
            ->with(['success' => __('Create success!')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view("category.view", ["category" => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view("category.edit", ["category" => $category]);
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
        $category = Category::findOrFail($id);

        $validate = [
            'name' => 'required',
            'order' => 'nullable|numeric',
        ];

        $request->validate($validate);

        $category->name = $request->name;
        $category->order = $request->order ?: 0;
        $category->save();

        return redirect()->route('categories.edit', ['category' => $category->id])
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
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->back();
    }
}
