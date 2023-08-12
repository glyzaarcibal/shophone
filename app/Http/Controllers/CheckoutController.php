<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Shipping;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    //
    public function index()
    {

        $shippings = Shipping::all();
        $payments = Payment::all();
        $cart = Cart::where('user_id', Auth::id())->get();
        return View::make('checkout.index', compact('cart', 'shippings', 'payments'));
    }

    public function checkOut(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = Auth::user()->id;
            $userCart = Cart::where('user_id', $user_id)->get();
            $newOrder = new Order;

            $newOrder->user_id = $user_id;
            $newOrder->shipping_id = $request->shipping_id;
            $newOrder->payment_id = $request->payment_id;
            $newOrder->address = $request->address;
            $newOrder->card_num = $request->card_num;
            $newOrder->save();

            $order_id = Order::max('id');

            foreach ($userCart as $cart) {
                DB::table('orderlines')->insert([
                    "order_id" => $order_id,
                    "product_id" => $cart->product_id,
                    "quantity" => $cart->quantity,
                ]);
                $product = Product::find($cart->product_id);
                $product->quantity = $product->quantity - $cart->quantity;
                $product->save();
            }
            Cart::where('user_id', $user_id)->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        DB::commit();
        // return redirect()->route('cartindex')->with('message', 'ORDER PLACED');
        return redirect()->route('cartindex')->with('message', 'Order placed successfully!');
    }


    public function orderView()
    {
        $user_id = Auth::id();
        $orders = DB::table('orderlines')
            ->join('products', 'orderlines.product_id', '=', 'products.id')
            ->join('orders', 'orderlines.order_id', '=', 'orders.id')
            ->where('orders.user_id', $user_id)
            ->select('orderlines.order_id', 'orderlines.product_id', 'orderlines.quantity', 'products.name', 'products.price', 'orders.status')
            ->get();

        $overallTotal = 0;
        foreach ($orders as $order) {
            $subtotal = $order->price * $order->quantity;
            $overallTotal += $subtotal;
        }

        return view('vieworders.index', compact('orders', 'overallTotal'));
    }

    public function cancelOrder($orderId)
    {
        $orders = Order::where('id', $orderId)->get();
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'No orders found with the specified order ID.');
        }

        // Cancel each order
        foreach ($orders as $order) {
            if ($order->status !== 'cancelled') {
                $order->status = 'cancelled';
                $order->save();
            }
        }
        return redirect()->back()->with('message', 'Orders cancelled successfully.');
    }

    public function orderHistory()
    {
        $user_id = Auth::id();
    
        $cancelledOrders = Order::where('status', 'cancelled')
            ->where('user_id', $user_id)
            ->join('orderlines', 'orders.id', '=', 'orderlines.order_id')
            ->join('products', 'orderlines.product_id', '=', 'products.id')
            ->select('orders.id', 'products.name', 'products.price', 'orderlines.quantity')
            ->get();
    
        return view('viewhistory.index', compact('cancelledOrders'));
    }
    
    public function adminView()
{
    // Retrieve all orders with pending status
    $orders = DB::table('orderlines')
        ->join('products', 'orderlines.product_id', '=', 'products.id')
        ->join('orders', 'orderlines.order_id', '=', 'orders.id')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->where('orders.status', '=', 'pending')
        ->select('orderlines.order_id', 'orderlines.product_id', 'orderlines.quantity', 'products.name',
         'products.price', 'orders.status', 'users.id as user_id', 'users.name as user_name')
        ->get();

    $overallTotal = 0;
    foreach ($orders as $order) {
        $subtotal = $order->price * $order->quantity;
        $overallTotal += $subtotal;
    }

    return view('vieworders.adminView', compact('orders', 'overallTotal'));
}

public function confirmOrder($orderId)
{
    $orders = Order::find($orderId);

    if ($orders && $orders->status === 'pending') {
        $orders->status = 'intransit';
        $orders->save();

        // Send email notification to user/customer
        // You can implement the email sending logic here

        return redirect()->back()->with('message', 'Order confirmed successfully.');
    }

    return redirect()->back()->with('error', 'Failed to confirm order.');
}













//     public function confirmOrder($orderId)
//     {
//         // Retrieve the order with the specified ID
//         $order = Order::findOrFail($orderId);

//         // Check if the order status is 'pending'
//         if ($order->status === 'pending') {
//             // Update the order status to 'in transit'
//             $order->status = 'in transit';
//             $order->save();

//             // Redirect back to the previous page with a success message
//             return redirect()->back()->with('message', 'Order confirmed successfully.');
//         }

//         // If the order status is not 'pending', redirect back with an error message
//         return redirect()->back()->with('error', 'Unable to confirm the order.');
//     }

}
