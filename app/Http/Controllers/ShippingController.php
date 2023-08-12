<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ShippingRequest;

class ShippingController extends Controller
{
    //
    public function index()
    {

        $shipment = Shipping::get();
        return view('shipping.index', compact('shipment'));
    }

    public function create()
    {
        return view('shipping.create');
    }

    public function store(ShippingRequest $request)
    {
        $validatedData = $request->validated();

        $method = new Shipping;
        $method->type = $validatedData['type'];


        if ($request->hasFile('delivery_image')) {
            $file = $request->file('delivery_image');

            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;

            $file->move('storage/uploads/brands', $filename);

            $method->delivery_image = $filename;

            $method->save();

            return redirect('shipping/index')->with('message', 'Shipping method Added Successfully!');
        }
    }

    public function edit($id){
        $ship = Shipping::where('id', '=', $id)->first();
        return view('shipping/edit', compact('ship'));
    }


    public function update(Request $request, $id)
    {

        $ship = Shipping::FindOrFail($id);

        $ship->type = $request->type;
      

        if ($request->hasFile('delivery_image')) {

            $path = 'storage/uploads/brands' . $ship->image;
            if (File::exists($path)) {
                File::delete($path);
            }
            $file = $request->file('delivery_image');

            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;

            $file->move('storage/uploads/brands', $filename);

            $ship->delivery_image = $filename;
        }
        $ship->save();

        return redirect()->route('viewshippings')->with('message', 'Shipping Method Updated Successfully!');
    }
    public function destroy($id)
    {

        Shipping::where('id', '=', $id)->delete();

        return redirect()->route('viewshippings')->with('message', 'Shipping Method Deleted Successfully!');
    }
}


