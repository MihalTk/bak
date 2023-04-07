<?php

namespace App\Http\Controllers;

use App\Charts\LiftedWeight;
use Illuminate\Http\Request;
use Auth;

class WelcomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $lifted_in_month = new LiftedWeight;
        $lifted_in_month->labels(['01.03.2023','01.03.2023','03.03.2023','06.03.2023','08.03.2023','10.03.2023','13.03.2023','16.03.2023','19.03.2023','23.03.2023','25.03.2023','27.03.2023']);
        $lifted_in_month->dataset('Lifted weight per month', 'bar', [100,105,106,150,123,156,200,140,153,124,160,210])->options([
            'backgroundColor' => 'rgba(0,172,118,0.9)',
            'opacity' => 1,
        ]);
        $lifted_in_month->dataset('Lifted weight per month', 'line', [100,105,106,150,123,156,200,140,153,124,160,210])->options([
            'backgroundColor' => 'rgba(0,172,118,0.5)',
        ]);

        $weight_year = new LiftedWeight;
        $weight_year->labels(['January','February','March','April','May','June','July','August','September','October','November','December']);
        $weight_year->dataset('Weight in months', 'line', [98.2,96.3,95.7,93,95.3,91.8,91.1,89.5,87.1,85,84.2,81.5])
        ->options([
            'responsive' => true,
            'opacity' => 0.1,
            'backgroundColor' => 'rgba(238, 80, 7, 0.76)',
        ]);


        return view('/welcome', compact('lifted_in_month','weight_year'));
    }
}
