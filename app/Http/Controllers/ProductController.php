<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('category')->get();
        
        if ($request->ajax()) {
            return DataTables::of($products)
                ->addColumn('category_name', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn-sm btn btn btn-info editButton" data-id="'.$row->id.'">Edit</a>
                    <a href="javascript:void(0)" class="btn-sm btn btn btn-danger delButton" data-id="'.$row->id.'">Delete</a>';
                  })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('products.index', compact('products'));
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Category::all();
        return view('products.products', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if($request->product_id != null){
            $product = Product::find($request->product_id);
            if(! $product) {
                abort(404);
            }
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->amount,
                'category_id' => $request->category_id,
            ]);
            return response()->json(['success' => 'Category Updated Successfull'], 201);
        }
        else{

            $request->validate([

                'name' => 'required|min:2|max:30',
               'description' => 'required',
               'amount' => 'required',
               'category_id' => 'required',
            ]);
    
            
            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->amount = $request->amount;
            $product->category_id = $request->category_id;
            $product->save();
            return response()->json(['success'=> 'product Save Successfully'], 201);
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
        $product = Product::find($id);
        if (! $product) {
            abort(404);
        }
        return response()->json($product);
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
        
        $product = Product::find($id);
        if (! $product) {
            abort(404);
        }

        $product->delete();

        return response()->json(['success' => 'Product Deleted Successfully'], 200);

    }
}
