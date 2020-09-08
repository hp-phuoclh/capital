<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use \DB;
class StoreController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct() {
        // permission
        $this->middleware('permission:add stores')->only('create', 'store');
        $this->middleware('permission:edit stores')->only('edit', 'update');
        $this->middleware('permission:show stores')->only('show','index');
        $this->middleware('permission:delete stores')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get client store
        $stores = Store::select("*");
        if ($request->get("search_keyword")) {
            $stores = $stores->where(function ($query) use ($request) {
                $query->whereRaw("LOWER(name) like ?", '%' . $request->get("search_keyword") . '%');
            });
            // save request when search
            $request->flash();
        }
        return view("store.index", ["stores" => $stores->paginate()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $store = new Store;
        return view("store.edit", ["store" => $store]);
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
            'address' => 'required',
            '*.*.price' => 'required|integer|min:0',
        ]);
        DB::beginTransaction();
        try {
            $store = new Store;
            $store->name = $request->name;
            $store->phone = $request->phone ?: '';
            $store->address = $request->address ?: '';
            $store->email = $request->email ?: '';
            $store->lat = $request->lat ?: 0;
            $store->long = $request->long ?: 0;
            $store->save();

            // save product
            if ($request->products) {
                foreach ($request->products as $key => $product) {
                    $store_product = new StoreProduct;
                    $store_product->store_id = $store->id;
                    $store_product->product_id = $product['id'];
                    $store_product->price = $product['price'];
                    $store_product->sale_off = $product['sale_off'];
                    $store_product->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            return redirect()->back()
                ->with(['error', $e->getMessage()]);
        }
        return redirect()->route('stores.create')
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
        $store = Store::findOrFail($id);
        return view("store.view", ["store" => $store]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $store = Store::findOrFail($id);
        return view("store.edit", ["store" => $store]);
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
        $store = Store::findOrFail($id);

        $validate = [
            'name' => 'required',
            'address' => 'required',
            '*.*.price' => 'required|integer|min:0',
        ];

        $request->validate($validate);

        DB::beginTransaction();
        try {
            $store->name = $request->name;
            $store->phone = $request->phone ?: '';
            $store->address = $request->address ?: '';
            $store->email = $request->email ?: '';
            $store->lat = $request->lat ?: 0;
            $store->long = $request->long ?: 0;
            $store->save();
            $store->store_products()->delete();
            // save product
            if ($request->products) {
                foreach ($request->products as $key => $product) {
                    $store_product = new StoreProduct;
                    $store_product->store_id = $store->id;
                    $store_product->product_id = $product['id'];
                    $store_product->price = $product['price'];
                    $store_product->sale_off = $product['sale_off'];
                    $store_product->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            return redirect()->back()
                ->with(['error', $e->getMessage()]);
        }
        return redirect()->route('stores.edit', ['store' => $store->id])
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
        $store = Store::findOrFail($id);

        $store->delete();

        return redirect()->back();
    }
}
