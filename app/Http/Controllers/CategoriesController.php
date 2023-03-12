<?php

namespace App\Http\Controllers;

use App\Helper\imageResizer;
use App\Helper\ResponseFormatter;
use App\Models\categoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{

    public function index() {
        $categories = categoryModel::all();

        return view('pages.categories.index', compact('categories'));
    }

    public function create() {
        return view('pages.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'bgColor' => 'nullable',
        ]);


        $image = $request->file('icon');
        $getImageName = imageResizer::ResizeImage($image, 'icon', 'icon', 80, 80);



        categoryModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $getImageName,
            'bgColor' => $request->bgColor,
        ]);




        return redirect()->route('categories.index')->with('success', 'Item created successfully!');;
    }
    // public function create(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'name' => ['required', 'string', 'max:50'],
    //         'icon' => ['required'],
    //         'bgColor' => ['required', 'string',],
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(
    //             [
    //                 'success' => false,
    //                 'message' => $validator->errors(),
    //             ],
    //             401
    //         );
    //     }

    //     if ($image = $request->file('icon')) {
    //         $getImageName = imageResizer::ResizeImage($image, 'icon', 'icon', 80, 80);

    //         //store your file into directory and db


    //         $categories = categoryModel::create([
    //             'name' => $request->name,
    //             'icon' => $getImageName,
    //             'bgColor' => $request->bgColor,
    //             'description' => $request->description,

    //         ]);
    //     } else {
    //         $categories = categoryModel::create([
    //             'name' => $request->name,
    //             'icon' => 'defaultIcon.png',
    //             'bgColor' => $request->bgColor,'description' => $request->description,

    //         ]);
    //     }

    //     return response()->json($categories);


    // }

    public function show($id)
    {
        $categories = categoryModel::find($id);

        return view('pages.categories.show', compact('categories'));
    }

    public function edit($id)
    {
        $category = categoryModel::find($id);

        return view('pages.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'description' => 'nullable',
            'image' => 'nullable|image',
            'bgColor' => 'nullable',
        ]);

        $category = categoryModel::find($id);
        $image_path = $category->icon;
        if ($request->hasFile('icon')) {
            $image = $request->file('icon');

            $getImageName = imageResizer::ResizeImage($image, 'icon', 'icon', 80, 80);

            $image_path =  $getImageName;
        }

        $category->name = $request->name;
        $category->description = $request->description;
        $category->bgColor = $request->bgColor;
        $category->icon = $image_path;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Item update successfully!');;
    }


    public function destroy  ($id)
    {
        $categories = categoryModel::find($id);
        $categories->delete();

        return redirect()->route('categories.index')->with('success', 'Item delete successfully!');;
    }

    // public function showCategoryWithProduct(){
    //     $categories = categoryModel::with("products")->select('id', 'name','icon')->get();

    //     return ResponseFormatter::success($categories, 'Berhasil mendapatkan data product');

    // }


    // public function all() {
    //     $categories = categoryModel::all();

    //     return ResponseFormatter::success($categories, 'berhasil');


    // }
}
