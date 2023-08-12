<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboardindex()
    {

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

        return view('admin.dashboard', compact("chartData","userData"));
    }



}
