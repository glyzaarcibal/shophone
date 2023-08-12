<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Support\Str;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
      public function index()
    {

        $payment = Payment::get();
        return view('payment.index', compact('payment'));
    }

    public function create()
    {
        return view('payment.create');
    }

    public function store(PaymentRequest $request)
    {
        $validatedData = $request->validated();

        $method = new Payment();
        $method->type = $validatedData['type'];


        if ($request->hasFile('payment_image')) {
            $file = $request->file('payment_image');

            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;

            $file->move('storage/uploads/brands', $filename);

            $method->payment_image = $filename;

            $method->save();

            return redirect('payment/index')->with('message', 'Payment method Added Successfully!');
        }
    }

    public function edit($id){
        $payment = Payment::where('id', '=', $id)->first();
        return view('payment/edit', compact('payment'));
    }


    public function update(Request $request, $id)
    {

        $payment = Payment::FindOrFail($id);

        $payment->type = $request->type;
      

        if ($request->hasFile('payment_image')) {

            $path = 'storage/uploads/brands' . $payment->payment_image;
            if (File::exists($path)) {
                File::delete($path);
            }
            $file = $request->file('payment_image');

            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;

            $file->move('storage/uploads/brands', $filename);

            $payment->payment_image = $filename;
        }
        $payment->save();

        return redirect()->route('viewpayments')->with('message', 'Payment Method Updated Successfully!');
    }
    public function destroy($id)
    {

        Payment::where('id', '=', $id)->delete();

        return redirect()->route('viewpayments')->with('message', 'Payment Method Deleted Successfully!');
    }
}
