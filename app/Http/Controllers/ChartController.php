<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{

    public Function ChartIndex(){

        $result = DB::select(DB::raw("SELECT COUNT(*) AS order_count, status FROM orders GROUP BY status"));

        $data = "";
        foreach ($result as $val) {
            $data .= "['".$val->status."', ". $val->order_count."],";
        }
        $chartData = $data;

        $userData = User::select(DB::raw("COUNT(*) as count"))
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw("Month(created_at)"))
        ->pluck('count');

        return view('chart.index', compact("chartData","userData"));
    }

    public function pieChart(Request $request)
{
    $result = DB::select(DB::raw("SELECT COUNT(*) AS order_count, status FROM orders GROUP BY status"));

    $data = "";
    foreach ($result as $val) {
        $data .= "['".$val->status."', ". $val->order_count."],";
    }

    $chartData = $data;

return view('chart.pie', compact("chartData"));

}

public function userChart(Request $request){

    $userData = User::select(DB::raw("COUNT(*) as count"))
    ->whereYear('created_at', date('Y'))
    ->groupBy(DB::raw("Month(created_at)"))
    ->pluck('count');

    return view('chart.userchart', compact('userData'));
}


}
