<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Image;
use File;
use Illuminate\Validation\Rule;

class SliderController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct() {
        // permission
        $this->middleware('permission:add sliders')->only('create', 'store');
        $this->middleware('permission:edit sliders')->only('edit', 'update');
        $this->middleware('permission:show sliders')->only('show','index');
        $this->middleware('permission:delete sliders')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view("slider.index", ["sliders" => $sliders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'image' => 'required',
        ]);
        
        $create = $request->all();
        $create['image_url'] = '';
        $slider = Slider::create($create);

        // upload images
        $file = $request->file('image');

        $slider->image_url = $this->uploadImageSlider($slider->id, '', $file);
        $slider->save();

        return redirect()->back()
            ->with(['success' => 'Create success!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        return response()->json($slider->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        return view("slider.edit", ["slider" => $slider]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image_edit' => Rule::requiredIf(!empty($request->file_delete)),
        ]);
        $slider->title = $request->title_edit;
        $slider->order = $request->order_edit ?: 0;
        $slider->url = $request->url_edit;
        $slider->save();

        if($request->file('image_edit') !== null ) {
            // upload images
            $file_delete = $request->file_delete ?: [$slider->image_url];
            $image = $request->file('image_edit');
            $slider->image_url = $this->uploadImageSlider($slider->id, $slider->image_url, $image, $file_delete);
            $slider->save();
        }
        return redirect()->back()
            ->with(['success' => __('Update successfully!')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();

        return redirect()->back();
    }

    /**
     * load list image slider.
     *
     * @return boolean
     */
    public function loadImages(Request $request, $id) {
        $slider = Slider::find($id);
        if(!$slider){
            return [];
        }
        $previews[] = (object)[
            'url' => asset($slider->image_url),
            'id' => 1,
            'path' => $slider->image_url,
        ];
        return  $previews;
    }

    /**
     * upload slider image
     *
     * @param array $file
     * @param integer $id
     * @return array
     */
    public function uploadImg($file, $id) {
        $path = '';
        if(!empty($file)) {
            $destinationPath = 'uploads/sliders/'.$id.'/';
            $guessExtension = $file->guessExtension();
            $filename = (md5_file($file->getRealPath())).time().'.'.$guessExtension;
            $file->move(public_path($destinationPath), $filename);
            $path = $destinationPath . $filename;
        }

        return $path;
    }

    /**
     * upload image slider
     *
     * @param array $file
     * @param array $fileDelete
     * @return array
     */ 
    public function uploadImageSlider($sliderId, $currentImage, $file, $fileDelete = [])
    {
        // delete file
        if($fileDelete){
            File::delete(\public_path($fileDelete[0]));
        }

        // upload new image
        $currentImage = $this->uploadImg($file, $sliderId);

        return $currentImage;
    }
}
