<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index($id)
    // {
     
    //     $cart = DB::table('carts')
    //     ->join('users','users.id','=','carts.user_id')
    //     ->join('products','products.id','=','carts.product_id')
    //     ->select('carts.*','products.image','products.name as productname','carts.quantity','products.price')
    //     ->where('user_id',$id)
    //     ->get();

    //     return view('cart.index',compact('cart'));
    // }
    public function index()
    {
        // $cart = DB::table('carts')
        //     ->join('products','products.id','=','carts.product_id')
        //     ->select('carts.*','products.image','products.name as productname','carts.quantity','products.price')
        //     ->where('user_id',Auth::id())
        //     ->get();
    $cart = Cart::where('user_id', Auth::id())->get();
        return view('cart.index', compact('cart'));
    }
    

    public function addcart($id)
    {

            $cart = Cart::where('user_id',auth()->id())
            ->where('product_id',$id)
            ->first();

            if($cart){
                $cart->increment('quantity');
            }else{
                $cart = Cart::create([
                    'user_id' =>auth()->id(),
                    'product_id' => $id,
                    'quantity' => 1,
                ]);
            }
            return redirect()->route('productcollection');
    }
        // public function updatecartItem(CartRequest $request,$id){
        //     $cartitem = Cart::FindOrFail($id);
        
        //     $cartitem->quantity = $request->quantity;

        //     $cartitem->save();

        // return redirect()->route('cartindex')->with('message','Product Updated Successfully!');
        // }
        public function updatecartItem(Request $request, $id)
        {
            $validatedData = $request->validate([
                'quantity' => 'required|numeric|min:1'
            ]);
        
            $cartItem = Cart::where('id', $id)->first();
        
            if (!$cartItem) {
                abort(404);
            }
        
            $cartItem->quantity = $validatedData['quantity'];
            $cartItem->save();
        
            return redirect()->route('cartindex')->with('success', 'Cart updated successfully.');
        }
        

        public function remove($id){
            Cart::where('id','=',$id)->delete();

            return redirect()->route('cartindex');
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
