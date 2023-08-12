<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
 
    public function index()
    {
        $brands = Brand::all();
        return view('collection.brandcategory',compact('brands')); 
}

public function productcollections()
{
    $brandproducts = DB::table('brands')->join('products','brands.id','=','products.brand_id')
    ->select('brands.name as brandname','brands.slug','brands.description','brands.image','products.id',
    'products.name','products.description','products.image','products.price','products.quantity','products.brand_id')
    ->get();
// dd($brandproducts);
    // $products = Product::all();
    return view('collection.products',compact('brandproducts'));
}

// public function viewcategory($slug){

//     if(Brand::where('slug',$slug)->exists()){
 
//       $brandcat =  Brand::where('slug',$slug)->first();
//       $products = Product::where('brand_id',$brandcat->id)->where('status','0')->get();
//       return view('collection.showproducts',compact('brandcat','products'));
//     }
//     else {
//         return redirect('/')->with('status','Slug does not exist');
//     }
    
// }
}
