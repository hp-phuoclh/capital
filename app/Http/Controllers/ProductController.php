<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Image;
use File;

class ProductController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct() {
        // permission
        $this->middleware('permission:add products')->only('create', 'store');
        $this->middleware('permission:edit products')->only('edit', 'update');
        $this->middleware('permission:show products')->only('show','index');
        $this->middleware('permission:delete products')->only('destroy');

        // view share
        $categories = Category::orderBy('order')->get()->pluck('name', 'id');
        view()->share('categories',$categories);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get client product
        $products = Product::select("*");
        if ($request->get("search_keyword")) {
            $products = $products->where(function ($query) use ($request){
                            $query->whereRaw("LOWER(name) like ?", '%'.$request->get("search_keyword").'%');
                            $query->orWhereRaw("LOWER(description) like ?", '%'.$request->get("search_keyword").'%');
                        });
            // save request when search
            $request->flash();
        }

        if ($request->get("category_id")) {
            $products->where("category_id", (int)$request->get("category_id"));
            // save request when search
            $request->flash();
        }
        return view("product.index", ["products" => $products->paginate()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product;
        return view("product.edit", ["product" => $product]);
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
            'category_id' => 'required',
            'code' => 'required|unique:products,code',
            'price' => 'required|numeric',
            'sale_off' => 'nullable|numeric',
        ]);

        $product = Product::create($request->all());

        // upload images
        $files = $request->file('files');
        $product->images = $this->uploadImageProduct($product->id, $product->images, $files);
        $product->save();

        return response()->json($product->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view("product.view", ["product" => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view("product.edit", ["product" => $product]);
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
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'sale_off' => 'nullable|numeric',
        ]);

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->sale_off = $request->sale_off;
        $product->sizes = $request->sizes ?: [];
        $product->save();

        // upload images
        $file_deletes = $request->file_deletes ?: [];
        $files = $request->file('files');
        $product->images = $this->uploadImageProduct($product->id, $product->images, $files, $file_deletes);
        $product->save();

        return response()->json($product->toArray());
        // return redirect()->route('products.edit', ['product' => $product->id])
            // ->with(['success' => __('Update successfully!')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->back();
    }

    /**
     * load list image product.
     *
     * @return boolean
     */
    public function loadImages(Request $request, $id) {
        $product = Product::find($id);
        if(!$product){
            return [];
        }
        $previews = [];
        foreach ($product->images as $key => $img) {
            $previews[$key] = (object)[
                'url' => asset($img),
                'id' => $key,
                'path' => $img,
            ];
        }
        return  $previews;
    }

    /**
     * load list all product.
     *
     * @return boolean
     */
    public function allProducts(Request $request) {
        $products = Product::all()->keyBy('id');
        
        return view("templates.products", ["products" => $products]);
    }

    /**
     * upload product image
     *
     * @param array $files
     * @param integer $id
     * @return array
     */
    public function uploadImg($files, $id) {
        $images = [];
        if(!empty($files)) {
            foreach($files as $image) {
                $destinationPath = 'uploads/products/'.$id.'/';
                $guessExtension = $image->guessExtension();
                $filename = (md5_file($image->getRealPath())).time().'.'.$guessExtension;
                $image->move(public_path($destinationPath), $filename);
                $path = $destinationPath . $filename;
                $images[] = $path;
            }
        }

        return $images;
    }

    /**
     * upload image product
     *
     * @param array $files
     * @param array $fileDeletes
     * @return array
     */ 
    public function uploadImageProduct($productId, $currentImages, $files, $fileDeletes = [])
    {
        // delete files
        if($fileDeletes){
            foreach ($fileDeletes as $pathDel) {
                File::delete(\public_path($pathDel));
                if (count($currentImages) > 0 && ($key = array_search($pathDel, $currentImages)) !== false) {
                    unset($currentImages[$key]);
                }
            }
        }

        // upload new image
        $currentImages = array_merge($currentImages, $this->uploadImg($files, $productId));

        return $currentImages;
    }
}
