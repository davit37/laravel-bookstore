<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = \App\Category::paginate(10);

        $filterKeyword = $request->get('name');
        


        if($filterKeyword){
            
            $categories = \App\Category::where('name', 'LIKE', "%$filterKeyword%")
                        ->paginate(10);
            
        }

        return view('categories.index', ['categories'=>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->get('name');

        $new_category = new \App\Category;
        $new_category->name = $name;
        $new_category->created_by=\Auth::user()->id;
        $new_category->slug=str_slug($name,'-');
      
        if($request->file('image')){
      
            $image_path = $request->file('image')
                    ->store('category_images', 'public');
      
            $new_category->image = $image_path;
        }

        $new_category->save();

        return redirect()->route('categories.create')->with('status', 'Category seccessfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories= \App\Category::findOrFail($id);

        return view('categories.edit', ['categories'=>$categories]);
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

        $category= \App\Category::findOrFail($id);

        $category->name = $request->get('name');
        $category->updated_by=\Auth::user()->id;
        $category->slug=str_slug($request->get('slug'),'-');

        if($request->file('image')){
            if($category->image && file_exists(storage_path('app/public/' . $category->image))){
                \Storage::delete('public/'.$category->image);
            }
            $file = $request->file('image')->store('category_images', 'public');
            $category->image = $file;
        }

        $category->save();

        return redirect()->route('categories.edit',['id' => $id])->with('status', 'Category seccessfully created');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = \App\Category::findOrFail($id);

        $category->delete();

        return redirect()->route('categories.index')
        ->with('status', 'Category successfully moved to trash');
    }


    public function trash(){
        $deleted_category = \App\Category::onlyTrashed()->paginate(10);

        return view('categories.trash', ['categories' => $deleted_category]);
    }

    public function restore($id){
        $category=\App\Category::withTrashed()
        ->findOrFail($id);

        if($category->trashed()){
            $category->restore();
        }else{
            return redirect()->route('categories.index')
        ->with('status', 'Category not in trash');
        }


        return redirect()->route('categories.index')
        ->with('status_restore', 'Category successfully restore');
    }

    public function deletePermanent($id){
        $category=\App\Category::withTrashed()
        ->findOrFail($id);

        if($category->trashed()){
            $category->forceDelete();
        }else{
            return redirect()->route('categories.trash')
        ->with('status', 'Can not delete permanent active category');
        }


        return redirect()->route('categories.trash')
        ->with('status_danger', 'Category successfully deleted');
    }

    public function ajaxSearch(Request $request){
        $keyword = $request->get('q');

        $categories=\App\Category::where('name','LIKE',"%$keyword%")->get();

        return $categories;

    }

    
}
