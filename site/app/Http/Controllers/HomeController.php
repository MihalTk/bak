<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Charts\LiftedWeight;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id=Auth::user()->id;


        $idTraining= DB::table('trainings')
        ->where('idUser', $id)->latest('created_at')->pluck('id')->first();

        $name = DB::table('trainings')
        ->where('idUser', $id)->latest('created_at')->pluck('training')->first();

        $exercises = DB::table('exercises')
        ->join('trainings_exercises','exercises.id','=','trainings_exercises.idExercise')
        ->join('trainings','trainings_exercises.idTraining','=','trainings.id')
        ->join('set_reps','trainings_exercises.id','=','set_reps.idTrainings_Exercise')
        ->select(['name','num_of_set','num_of_reps','weight'])
        ->where('trainings.id',$idTraining)->get();

        $none = DB::table('exercises')
        ->where('name','a')
        ->get();

        $cardio_db = DB::table('exercises')
        ->join('trainings_exercises','exercises.id','=','trainings_exercises.idExercise')
        ->join('trainings','trainings_exercises.idTraining','=','trainings.id')
        ->select('name','min')
        ->where('trainings.id',$idTraining)
        ->where('trainings_exercises.min','!=',null)
        ->get();

        if($cardio_db!=$none){
            $cardio[] = ['name' => $cardio_db[0]->name, 'min' => $cardio_db[0]->min];
        }
        else{
            $cardio[] = ['name' => 'none', 'min' => 0];
        }

        $lifeted_in_year = DB::table('trainings')
        ->join('trainings_exercises','trainings.id','=','trainings_exercises.idTraining')
        ->join('set_reps','trainings_exercises.id','=','set_reps.idTrainings_Exercise')
        ->select(DB::raw("DATE_FORMAT(trainings.created_at, '%Y-%m') as month"), DB::raw("sum(set_reps.weight) as sum"))
        ->where('trainings.created_at','>',Carbon::now()->subYear())
        ->where('trainings.idUser',$id)
        ->groupBy('month')
        ->pluck('sum','month');

        $lifeted_in_month = DB::table('trainings')
        ->join('trainings_exercises','trainings.id','=','trainings_exercises.idTraining')
        ->join('set_reps','trainings_exercises.id','=','set_reps.idTrainings_Exercise')
        ->select(DB::raw('SUM(set_reps.weight) as sum, DATE(trainings.created_at) as date'))
        ->where('trainings.created_at','>',Carbon::now()->subMonths(1))
        ->where('trainings.idUser',$id)
        ->groupBy('date')
        ->pluck('sum','date');

        $lifeted_in_six = DB::table('trainings')
        ->join('trainings_exercises','trainings.id','=','trainings_exercises.idTraining')
        ->join('set_reps','trainings_exercises.id','=','set_reps.idTrainings_Exercise')
        ->select(DB::raw("DATE_FORMAT(trainings.created_at, '%Y-%m-%w') as month"), DB::raw("sum(set_reps.weight) as sum"))
        ->where('trainings.created_at','>',Carbon::now()->subMonths(6))
        ->where('trainings.idUser',$id)
        ->groupBy('month')
        ->pluck('sum','month');

        $weight_in_year = DB::table('weights')
        ->where('idUser', $id)
        ->select(DB::raw('DATE(created_at) as date, weight'))
        ->where('created_at', '>', Carbon::now()->subYear())
        ->oldest('date')
        ->pluck('weight','date');

        $cardio_min = DB::table('trainings')
        ->join('trainings_exercises','trainings.id','=','trainings_exercises.idTraining')
        ->join('exercises','exercises.id','=','trainings_exercises.idExercise')
        ->join('types','types.id','=','exercises.idType')
        ->select(DB::raw('SUM(trainings_exercises.min) as sum, exercises.name'))
        ->where('trainings.created_at','>',Carbon::now()->subYear())
        ->where('types.name','cardio')
        ->where('trainings.idUser',$id)
        ->groupBy('exercises.name')
        ->havingRaw('sum IS NOT null')
        ->pluck('sum','exercises.name');

        if($exercises!=$none){
            $final = $this->final($exercises);
            $data = array(
                'name' => $name,
                'cardio' => $cardio,
                'final'=>$final,
                'ex' => 'some',
            );
        }
        else{
            $final = array();
            $data = array(
                'name' => $name,
                'cardio' => $cardio,
                'final' => $final,
                'ex' => 'none',
            ); 
        }
        

        $chart_last_year = new LiftedWeight;
        $chart_last_year->labels($lifeted_in_year->keys());
        $chart_last_year->dataset('Lifted weight in months', 'line', $lifeted_in_year->values())->options([
            'backgroundColor' => 'rgba(0,172,118,0.5)',
        ]);

        $chart_last_month = new LiftedWeight;
        $chart_last_month->labels($lifeted_in_month->keys());
        $chart_last_month->dataset('Lifted weight per day', 'line', $lifeted_in_month->values())->options([
            'backgroundColor' => 'rgba(0,172,118,0.5)',
        ]);

        $chart_last_six = new LiftedWeight;
        $chart_last_six->labels($lifeted_in_six->keys());
        $chart_last_six->dataset('Lifted weight per day', 'line', $lifeted_in_six->values())->options([
            'backgroundColor' => 'rgba(0,172,118,0.5)',
        ]);

        $chart_weight_year = new LiftedWeight;
        $chart_weight_year->labels($weight_in_year->keys());
        $chart_weight_year->dataset('Weight in year', 'line', $weight_in_year->values())->options([
            'backgroundColor' => 'rgba(238, 80, 7, 0.76)',
        ]);

        $chart_cardio = new LiftedWeight;
        $chart_cardio->labels($cardio_min->keys());
        $chart_cardio->dataset('Cardio in minutes', 'bar', $cardio_min->values())->options([
            'responsive' => true,
            'backgroundColor' => ['rgba(255,128,0,0.8)','rgba(0,204,0,0.8)','rgba(0,128,255,0.8)',
            'rgba(127,0,255,0.8)','rgba(255,0,127,0.8)','rgba(153,0,0,0.8)','rgba(153,153,255,0.8)','rgba(0,102,51,0.8)'],
        ]);



        return view('/home', compact('chart_last_year','chart_last_month','chart_last_six','chart_weight_year','chart_cardio'))->with($data);
    }

    private function final($exercises){

        $final = array();
        $final_exercises = array();        
        $name_old = $exercises[0]->name;
        $final_exercises[] = $name_old;

        for($i=0; $i < count($exercises); $i++){
            $name_new = $exercises[$i]->name;
            
            if($name_old==$name_new){
                $final_exercises[] = ['weight' => $exercises[$i]->weight, 'reps' => $exercises[$i]->num_of_reps, 'sets' => $exercises[$i]->num_of_set];
            }
            else{
                $final[] = $final_exercises;
                $final_exercises = array();
                $final_exercises[] = $name_new;
                $final_exercises[] = ['weight' => $exercises[$i]->weight, 'reps' => $exercises[$i]->num_of_reps, 'sets' => $exercises[$i]->num_of_set];
            }
            $name_old=$name_new;
        }
        $final[] = $final_exercises;
        
        return $final;
    }
}