<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use DB;
use Illuminate\Support\Facades\Validator;

class DropzoneController extends Controller
{

    public function __construct()
    {
        $this->image_model = new Image();
    }

    //
    public function index()
    {
        return view('dropzone');
    }

    public function fetch()
    {
        $items = $this->image_model->getAll();
        $html = view('list_images', ['items' => $items]);
        return response()->json([
            'html' => $html->render()
        ], 200);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'file.*' => 'mimes:jpeg,jpg,png,gif,csv,txt,pdf|max:2048'
        ]);
        try {
            if($request->hasfile('file')) {
                DB::beginTransaction();
                foreach($request->file('file') as $file)
                {
                    $ext = $file->getClientOriginalExtension();
                    $name = uniqid().'.'.$ext;
                    $file_path = 'uploads'.'/'. $name;
                    $store_path = public_path().'/uploads';
                    $file->move($store_path, $name);  
                    
                    $file_modal = new Image();
                    $file_modal->name = $name;
                    $file_modal->image_path = $file_path;
                    $file_modal->save();
                    $imgData[] = $name;  
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'upload images fail.'
            ], 500);
        }
        return response()->json([
            'file_names' => $imgData
        ], 200);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            abort(400);
        }
        $image = $this->image_model->byId($request->input('images_id'));
        if(!$image) {
            abort(400);
        }
        try {
            DB::beginTransaction();
            $image->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        return response()->json([], 200);
    }
}
