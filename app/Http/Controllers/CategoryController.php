<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::all();
    
            return DataTables::of($categories)
                ->addColumn('action', function($row) {
                    return '<a href="javascript:void(0)" class="btn-sm btn btn btn-info editButton" data-id="'.$row->id.'">Edit</a>
                    <a href="javascript:void(0)" class="btn-sm btn btn btn-danger delButton" data-id="'.$row->id.'">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('category.index');
    }
    


    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if($request->category_id != null){
            $category = Category::find($request->category_id);
            if(! $category) {
                abort(404);
            }
            $category->update([
                'name' => $request->name,
                'type' => $request->type,
            ]);
            return response()->json(['success' => 'Category Updated Successfull'], 201);
        }
        else{

            $request->validate([

                'name' => 'required|min:2|max:30',
                'type' => 'required',
            ]);
    
            
            $category = new Category();
            $category->name = $request->name;
            $category->type = $request->type;
            $category->save();
            return response()->json(['success'=> 'Category Save Successfully'], 201);
        }

       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        if(! $category) {
            abort(404);
        }
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $category = Category::find($id);
        if(! $category) {
            abort(404);
        }
         $category->delete();
         return response()->json(['success'=> 'Category Delete Successfully'], 201);

    }
}
