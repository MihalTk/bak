<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Database\Seeders\Set_Reps;
use Illuminate\Http\Request;
use Auth;
use App\Models\Training;
use App\Models\TrainingsExercise;
use App\Models\SetRep;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class NextController extends Controller
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

    public function whichsubmit(){
    }

    public function index(Request $request)
    {
        if($request->submit!=null){
            switch(explode('-',$request->submit)[0]){
                case('save'):
                    $this->saveTraining($request);
                    return redirect('/home');
                case('delete'):
                    $data = $this->exercise_delete($request);
                    break;
                case('addset'):
                    $data = $this->set_add($request);
                    break;
                case('deleteset'):
                    $data = $this->set_delete($request);
                    break;
                case('cardiodelete'):
                    $data = $this->cardio_delete($request);
                    break;
                case('cardioadd'):
                    $data = $this->cardio_add($request);
                    break;
                case('exerciseaddpart'):
                    $data = $this->exercise_add_part($request);
                    break;
                case('exerciseaddid'):
                    $data = $this->exercise_add_id($request);
                    break;
            }
        }
        else{
            $data = $this->generate_training();
        }
        
        return view('/next')->with($data);
    }

    private function exercise_add_id(Request $request){

        $id = $request['exerciseid'];
        $request->request->remove('part');
        $request->request->remove('submit');
        $request->request->remove('exerciseid');
        $request->request->remove('idcardio');

        if(count($request->request)>2){
            $data = $this->make_data($request);
            
        }
        else{
            $training_name = $request['training-name'];
            $data = array(
                'name' => $training_name,
            );
        }

        if($id!=null){
            $exercises = DB::table('exercises')
            ->where('id',$id)
            ->get();
        }
        else{
            $exercises[] = DB::table('exercises')
            ->where('idprimary_exercised','!=',null)
            ->inRandomOrder()
            ->first(['name','id']);
        }

        $data['final'][] = $this->new_exercise_add($exercises);
        $data['ex'] = 'some';

        $cardio = $this->cardio($request);
        $data['cardio'] = $cardio;

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment'); 

        $data['all_cardio'] = $this->get_all_cardio($user_equipments);

        $all_exercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises_has_equipments.idExercise','=','exercises.id')
        ->join('equipments','equipments.id','=','exercises_has_equipments.idEquipment')
        ->join('usershasequipments','usershasequipments.idEquipment','=','equipments.id')
        ->join('users','users.id','=','usershasequipments.idUser')
        ->whereNot('exercises.idprimary_exercised',null)
        ->distinct('exercises.id')
        ->orderBy('exercises.name')
        ->get(['exercises.id','exercises.name']);

        $data['allexercises'] = $all_exercises;

        return $data;

    }

    private function exercise_add_part(Request $request){

        $part = $request['part'];
        $request->request->remove('part');
        $request->request->remove('submit');
        $request->request->remove('exerciseid');
        $request->request->remove('idcardio');

        if(count($request->request)>2){
            $data = $this->make_data($request);
        }
        else{
            $training_name = $request['training-name'];
            $data = array(
                'name' => $training_name,
            );
        }

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment');

        $user_experience = DB::table('users')
        ->where('id', Auth::user()->id)
        ->pluck('idExperience')[0];

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment');

        $exercises = DB::table('exercises')
        ->where('idprimary_exercised','=',100)
        ->get();

        if($part!=null){
            $exercises = $this->generate_exercises($part, $user_equipments, $exercises, 1, $user_experience);
        }
        else{
            $exercises[] = DB::table('exercises')
            ->where('idprimary_exercised','!=',null)
            ->inRandomOrder()
            ->first(['name','id']);
        }

        $data['final'][] = $this->new_exercise_add($exercises);
        $data['ex'][] = 'some';

        $cardio = $this->cardio($request);
        $data['cardio'] = $cardio;

        $all_cardio = $this->get_all_cardio($user_equipments);
        $data['all_cardio'] = $all_cardio;

        $all_exercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises_has_equipments.idExercise','=','exercises.id')
        ->join('equipments','equipments.id','=','exercises_has_equipments.idEquipment')
        ->join('usershasequipments','usershasequipments.idEquipment','=','equipments.id')
        ->join('users','users.id','=','usershasequipments.idUser')
        ->whereNot('exercises.idprimary_exercised',null)
        ->distinct('exercises.id')
        ->orderBy('exercises.name')
        ->get(['exercises.id','exercises.name']);

        $data['allexercises'] = $all_exercises;
        
        return $data;
    }

    private function new_exercise_add($exercises){
        $last_exercised[] = DB::table('trainings')
        ->join('trainings_exercises','trainings.id','=','trainings_exercises.idTraining')
        ->where('trainings.idUser', Auth::user()->id)
        ->where('trainings_exercises.idExercise',$exercises[0]->id)
        ->latest('trainings.created_at')
        ->first(['trainings_exercises.id','trainings_exercises.idExercise']);

        $final_exercises[] = $exercises[0]->name;

        if($last_exercised[0]!=null){
            $sets[]=DB::table('set_reps')
            ->join('trainings_exercises','trainings_exercises.id','=','set_reps.idTrainings_Exercise')
            ->join('exercises','exercises.id','=','trainings_exercises.idExercise')
            ->where('trainings_exercises.id',$last_exercised[0]->id)
            ->where('trainings_exercises.idExercise',$last_exercised[0]->idExercise)
            ->select('trainings_exercises.id','name','trainings_exercises.idExercise','num_of_set','weight','num_of_reps')
            ->get();
            if(count($sets[0])>3){
                for($j=0; $j < count($sets[0]); $j++){
                    $final_exercises[] = ['idExercise' => $sets[0][$j]->idExercise, 'weight' => $sets[0][$j]->weight, 'reps' => $sets[0][$j]->num_of_reps, 'sets' => $j+1];
                }
            }
            else{
                switch(count($sets[0])){
                    case(1):
                        $final_exercises[] = ['idExercise' => $sets[0][0]->idExercise, 'weight' => $sets[0][0]->weight, 'reps' => $sets[0][0]->num_of_reps, 'sets' => 1];
                        for($j=1; $j < 4; $j++){
                            $final_exercises[] = ['idExercise' => $exercises[0]->id, 'weight' => $sets[0][0]->weight, 'reps' => 15-$j*2, 'sets' => $j+1];
                        }
                        break;
                    case(2):
                        for($j=0; $j < count($sets[0]); $j++){
                            $final_exercises[] = ['idExercise' => $sets[0][$j]->idExercise, 'weight' => $sets[0][$j]->weight, 'reps' => $sets[0][$j]->num_of_reps, 'sets' => $j+1];
                        }
                        for($j=2; $j < 4; $j++){
                            $final_exercises[] = ['idExercise' => $exercises[0]->id, 'weight' => $sets[0][1]->weight, 'reps' => 15-$j*2, 'sets' => $j+1];
                        }
                        break;
                    case(3):
                        for($j=0; $j < count($sets[0]); $j++){
                            $final_exercises[] = ['idExercise' => $sets[0][$j]->idExercise, 'weight' => $sets[0][$j]->weight, 'reps' => $sets[0][$j]->num_of_reps, 'sets' => $j+1];
                        }
                        $final_exercises[] = ['idExercise' => $exercises[0]->id, 'weight' => $sets[0][2]->weight, 'reps' => 9, 'sets' => 4];
                        break;
                }
            }
            
        }
        else{ 
            for($j=0; $j < 4; $j++){
                $final_exercises[] = ['idExercise' => $exercises[0]->id, 'weight' => 0, 'reps' => 15-$j*2, 'sets' => $j+1];
            }
        }

        return $final_exercises;
    }

    private function cardio_add(Request $request){
        $id = $request['idcardio'];

        $request->request->remove('submit');
        $request->request->remove('part');
        $request->request->remove('exerciseid');
        $request->request->remove('idcardio');

        $user_experience = DB::table('users')
        ->where('id', Auth::user()->id)
        ->pluck('idExperience')[0];

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment');
        
        $days = DB::table('days_per_weeks')
        ->where('idUser', Auth::user()->id)
        ->get(['days','updated_at']);

        $user_type = DB::table('users_goals')
        ->join('types','types.id','=','users_goals.idType')
        ->where('idUser',Auth::user()->id)
        ->get('types.name')[0]->name;


        switch($days[0]->days){
            case (1):
                switch($user_type){
                    case('bodybuilding'):
                        $min = 10;
                        break;
                    case('loose weight'):
                        $min = 20;
                        break;
                    case('endurance'):
                        $min = 20;
                        break;
                }
                break;
            case (2):
                switch($user_type){
                    case('bodybuilding'):
                        $min = 10;
                        break;
                    case('loose weight'):
                        $min = 20;
                        break;
                    case('endurance'):
                        $min = 20;
                        break;
                }
                break;
            case (3):
                switch($user_type){
                    case('bodybuilding'):
                        $min = 8;
                        break;
                    case('loose weight'):
                        $min = 15;
                        break;
                    case('endurance'):
                        $min = 15;
                        break;
                }
                break;
            case (4):
                switch($user_type){
                    case('bodybuilding'):
                        $min = 7;
                        break;
                    case('loose weight'):
                        $min = 15;
                        break;
                    case('endurance'):
                        $min = 15;
                        break;
                }
                break;
            case (5):
                switch($user_type){
                    case('bodybuilding'):
                        $min = 5;
                        break;
                    case('loose weight'):
                        $min = 12;
                        break;
                    case('endurance'):
                        $min = 12;
                        break;
                }
                break;
            case (6):
                switch($user_type){
                    case('bodybuilding'):
                        $min = 5;
                        break;
                    case('loose weight'):
                        $min = 10;
                        break;
                    case('endurance'):
                        $min = 10;
                        break;
                }
                break;
        }

        if($id!=null){
            $cardio_help = DB::table('exercises')
            ->where('id',$id)
            ->get(['id','name']);

            $cardio[] = ['is_cardio' => 'yes', 'id' => $cardio_help[0]->id, 'name' => $cardio_help[0]->name, 'min' => $min];
        }
        else{
            $cardio = $this->generate_cardio('cardio',  $user_equipments, 1, $user_experience, $min);
        }

        if(count($request->request)>2){
            $data = $this->make_data($request);
        }
        else{
            $training_name = $request['training-name'];
            $data = array(
                'name' => $training_name,
                'ex' => 'none',
            );
        }

        $all_exercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises_has_equipments.idExercise','=','exercises.id')
        ->join('equipments','equipments.id','=','exercises_has_equipments.idEquipment')
        ->join('usershasequipments','usershasequipments.idEquipment','=','equipments.id')
        ->join('users','users.id','=','usershasequipments.idUser')
        ->whereNot('exercises.idprimary_exercised',null)
        ->distinct('exercises.id')
        ->orderBy('exercises.name')
        ->get(['exercises.id','exercises.name']);
        $data['allexercises'] = $all_exercises;
        
        $data['cardio'] = $cardio;

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment');

        $all_cardio = $this->get_all_cardio($user_equipments);
        $data['all_cardio'] = $all_cardio;

        return $data;
    }

    private function cardio_delete(Request $request){                
        
        $request->request->remove('submit');
        $request->request->remove('part');
        $request->request->remove('exerciseid');
        $request->request->remove($request->request->keys()[count($request->request->keys())-1]);


        if(count($request->request)>2){
            $data = $this->make_data($request);
        }
        else{
            $training_name = $request['training-name'];
            $data = array(
                'name' => $training_name,
                'ex' => 'none',
            );
        }

        $cardio[]= ['is_cardio' => 'no'];
        $data['cardio'] = $cardio;

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment');

        $all_cardio = $this->get_all_cardio($user_equipments);
        $data['all_cardio'] = $all_cardio;

        $all_exercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises_has_equipments.idExercise','=','exercises.id')
        ->join('equipments','equipments.id','=','exercises_has_equipments.idEquipment')
        ->join('usershasequipments','usershasequipments.idEquipment','=','equipments.id')
        ->join('users','users.id','=','usershasequipments.idUser')
        ->whereNot('exercises.idprimary_exercised',null)
        ->distinct('exercises.id')
        ->orderBy('exercises.name')
        ->get(['exercises.id','exercises.name']);

        $data['allexercises'] = $all_exercises;
        // return dd($data,$data['is_cardio']=='none');
        return $data;
    }

    private function set_add(Request $request){

        $id = explode('-',$request->submit)[1];

        $request->request->remove('submit');
        $request->request->remove('part');
        $request->request->remove('exerciseid');
        
        $data = $this->make_data($request);

        for($i=0; $i<count($data['final']);$i++){
            if($data['final'][$i][1]['idExercise']==$id){
                $data['final'][$i][] = ['idExercise' => $id, 'weight' => $data['final'][$i][count($data['final'][$i])-1]['weight'], 'reps' =>  $data['final'][$i][count($data['final'][$i])-1]['reps'], 'sets' => $data['final'][$i][count($data['final'][$i])-1]['sets']+1];
            }
        }

        $cardio = $this->cardio($request);
        $data['cardio'] = $cardio;

        $all_exercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises_has_equipments.idExercise','=','exercises.id')
        ->join('equipments','equipments.id','=','exercises_has_equipments.idEquipment')
        ->join('usershasequipments','usershasequipments.idEquipment','=','equipments.id')
        ->join('users','users.id','=','usershasequipments.idUser')
        ->whereNot('exercises.idprimary_exercised',null)
        ->distinct('exercises.id')
        ->orderBy('exercises.name')
        ->get(['exercises.id','exercises.name']);

        $data['allexercises'] = $all_exercises;
    
        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment');

        $all_cardio = $this->get_all_cardio($user_equipments);
        $data['all_cardio'] = $all_cardio;

        return $data;
    }

    private function set_delete(Request $request){
        $id = explode('-',$request->submit)[1];
        
        $request->request->remove('submit');
        $request->request->remove('part');
        $request->request->remove('exerciseid');
        $request->request->remove('idcardio');

        $cardio = $this->cardio($request);

        

        $count = 0;
        for($i=count($request->request)-1; $i>=0; $i--){
            if(str_contains($request->request->keys()[$i],'-')){
                if(explode('-',$request->request->keys()[$i])[1] == $id){
                    $count++;
                }
            }
        }

        

        $count/=2;
        $request->request->remove('weight-'.$id.'-'.$count);
        $request->request->remove('reps-'.$id.'-'.$count);

        
        if(count($request->request)>2){
            $data = $this->make_data($request);
        }
        else{
            $training_name = $request['training-name'];
            $data = array(
                'name' => $training_name,
                'ex' => 'none',
            );
        }

        $data['cardio'] = $cardio;

        $all_exercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises_has_equipments.idExercise','=','exercises.id')
        ->join('equipments','equipments.id','=','exercises_has_equipments.idEquipment')
        ->join('usershasequipments','usershasequipments.idEquipment','=','equipments.id')
        ->join('users','users.id','=','usershasequipments.idUser')
        ->whereNot('exercises.idprimary_exercised',null)
        ->distinct('exercises.id')
        ->orderBy('exercises.name')
        ->get(['exercises.id','exercises.name']);

        $data['allexercises'] = $all_exercises;

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment');

        $all_cardio = $this->get_all_cardio($user_equipments);
        $data['all_cardio'] = $all_cardio;

        return $data;
    }
    private function exercise_delete(Request $request){        
        $id = explode('-',$request->submit)[1];
        
        $request->request->remove('submit');
        $request->request->remove('part');
        $request->request->remove('exerciseid');
        $request->request->remove('idcardio');
        
        $cardio = $this->cardio($request);

        for($i=count($request->request)-1; $i>=0; $i--){
            if(str_contains($request->request->keys()[$i],'-')){
                if(explode('-',$request->request->keys()[$i])[1] == $id){
                    $request->request->remove($request->request->keys()[$i]);
                }
            }
        }

        if(count($request->request)>2){
            $data = $this->make_data($request);
        }
        else{
            $training_name = $request['training-name'];
            $data = array(
                'name' => $training_name,
                'ex' => 'none',
            );
        }

        $all_exercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises_has_equipments.idExercise','=','exercises.id')
        ->join('equipments','equipments.id','=','exercises_has_equipments.idEquipment')
        ->join('usershasequipments','usershasequipments.idEquipment','=','equipments.id')
        ->join('users','users.id','=','usershasequipments.idUser')
        ->whereNot('exercises.idprimary_exercised',null)
        ->distinct('exercises.id')
        ->orderBy('exercises.name')
        ->get(['exercises.id','exercises.name']);

        $data['allexercises'] = $all_exercises;

        $data['cardio'] = $cardio;

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser', Auth::user()->id)
        ->get('idEquipment');

        $all_cardio = $this->get_all_cardio($user_equipments);
        $data['all_cardio'] = $all_cardio;

        return $data;
    }

    private function cardio(Request $request){
        $cardio = array();
        for($i=count($request->request)-1; $i>=0; $i--){
            if(str_contains($request->request->keys()[$i],'-')){
                if(explode('-',$request->request->keys()[$i])[0] == 'cardio'){

                    $cardio_db = DB::table('exercises')
                    ->where('id',explode('-',$request->request->keys()[$i])[1])
                    ->select('name','id')
                    ->get();

                    $cardio[] = ['is_cardio' => 'yes', 'id' => $cardio_db[0]->id, 'name' => $cardio_db[0]->name, 'min' => $request[$request->request->keys()[$i]]];

                    $request->request->remove($request->request->keys()[$i]);
                }
            }
        }
        if($cardio==[]){
            $cardio[] = ['is_cardio' => 'no'];
        } 

        return $cardio;
    }

    private function make_data(Request $request){
        $training_name = $request['training-name'];
        $final = array();
        $final_exercises = array();
        
        $id_old = explode('-',$request->request->keys()[2])[1];
        $final_exercises[] = DB::table('exercises')
            ->where('id',$id_old)
            ->select('name')
            ->get()[0]->name;

        for($i=2; $i < count($request->request)-1; $i++){
            $id_new = explode('-',$request->request->keys()[$i])[1];
            $weightKey = $request->request->keys()[$i];
            $name = DB::table('exercises')
            ->where('id',$id_new)
            ->select('name')
            ->get();
            $repsKey = $request->request->keys()[$i+1];
            $set = explode('-',$request->request->keys()[$i])[2];
            if($id_old==$id_new){
                $final_exercises[] = ['idExercise' => $id_new, 'weight' => $request[$weightKey], 'reps' =>  $request[$repsKey], 'sets' => $set];
            }
            else{
                $final[] = $final_exercises;
                $final_exercises = array();
                $final_exercises[] = $name[0]->name;
                $final_exercises[] = ['idExercise' => $id_new, 'weight' => $request[$weightKey], 'reps' =>  $request[$repsKey], 'sets' => $set];
            }
            $id_old=$id_new;
            $i++;
        }
        $final[] = $final_exercises;

        $data = array(
            'name' => $training_name,
            'final'=> $final,
            'ex' => 'some',
        );

        return $data;
    }

    //Generate new training //
    private function generate_training(){
        $id=Auth::user()->id;

        $days = DB::table('days_per_weeks')
        ->where('idUser', $id)
        ->get(['days','updated_at']);

        $name_of_last_training = DB::table('trainings')
        ->where('idUser',Auth::user()->id)
        ->latest('created_at')
        ->pluck('training')
        ->first();

        if($name_of_last_training!=null){
            $latest_training_date = DB::table('trainings')
            ->where('idUser',Auth::user()->id)
            ->latest('created_at')
            ->get('created_at')
            ->first();

            if($days[0]->updated_at>$latest_training_date->created_at){
                $name_of_last_training=null;
            }
        }

        $user_equipments = DB::table('usershasequipments')
        ->where('idUser',$id)
        ->get('idEquipment');

        $exercises = DB::table('exercises')
        ->where('idprimary_exercised','=',100)
        ->get();

        $user_experience = DB::table('users')
        ->where('id',$id)
        ->pluck('idExperience')[0];

        $data = array();

        $user_type = DB::table('users_goals')
        ->join('types','types.id','=','users_goals.idType')
        ->where('idUser',Auth::user()->id)
        ->get('types.name')[0]->name;


        switch($days[0]->days){
            case(1):
                $data = $this->everything($user_equipments, $exercises, $user_experience, $user_type);
                break;

            case(2):
                $data = $this->upper_lower_split($name_of_last_training, $user_equipments,$exercises, $user_experience, $user_type);
                break;

            case(3):
                $data = $this->ppl_split($name_of_last_training, $user_equipments, $exercises, $user_experience);

                switch($user_type){
                    case('bodybuilding'):
                        $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 8);
                        break;
                    case('loose weight'):
                        $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 15);
                        break;
                    case('endurance'):
                        $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 15);
                        break;
                }

                $data['cardio'] = $cardio;
                break;

            case(4):

                $data = $this->ppal_split($name_of_last_training, $user_equipments, $exercises, $user_experience, $user_type);
                break;

            case(5):

                $data = $this->five_day_split($name_of_last_training, $user_equipments, $exercises, $user_experience, $user_type);
                break;

            case(6):
                $data = $this->ppl_split($name_of_last_training, $user_equipments, $exercises, $user_experience);

                switch($user_type){
                    case('bodybuilding'):
                        $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 5);
                        break;
                    case('loose weight'):
                        $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 10);
                        break;
                    case('endurance'):
                        $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 10);
                        break;
                }

                $data['cardio'] = $cardio;
                break;
        }

        $all_exercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises_has_equipments.idExercise','=','exercises.id')
        ->join('equipments','equipments.id','=','exercises_has_equipments.idEquipment')
        ->join('usershasequipments','usershasequipments.idEquipment','=','equipments.id')
        ->join('users','users.id','=','usershasequipments.idUser')
        ->whereNot('exercises.idprimary_exercised',null)
        ->distinct('exercises.id')
        ->orderBy('exercises.name')
        ->get(['exercises.id','exercises.name']);

        $data['allexercises'] = $all_exercises;

        $all_cardio = $this->get_all_cardio($user_equipments);
        $data['all_cardio'] = $all_cardio;

        return $data;
    }

    private function saveTraining(Request $request)
    {
        $training = Training::create([
            'training' => $request['training-name'],
            'idUser' => Auth::user()->id,
        ]);

        $request->request->remove('submit');
        $request->request->remove('part');
        $request->request->remove('exerciseid');
        $request->request->remove('idcardio');


        $ids = [];
        for ($i=2; $i < count($request->request); $i++) { 
            if(!in_array(explode('-',$request->request->keys()[$i])[1],$ids) && explode('-',$request->request->keys()[$i])[0] != 'cardio'){
                $ids[] = explode('-',$request->request->keys()[$i])[1];
            }
            if(explode('-',$request->request->keys()[$i])[0] == 'cardio'){
                $trainings_exercise = TrainingsExercise::create([
                    'idTraining' => $training->id,
                    'idExercise' => explode('-',$request->request->keys()[$i])[1],
                    'min' => $request['cardio-'.explode('-',$request->request->keys()[$i])[1]],
                ]);
                $request->request->remove('cardio-'.explode('-',$request->request->keys()[$i])[1]);
            }
        }

        foreach($ids as $id){
            $trainings_exercise = TrainingsExercise::create([
                'idTraining' => $training->id,
                'idExercise' => $id,
            ]);
            $num_of_set = 1;
            for($i=2; $i < count($request->request); $i++){
                if(explode('-', $request->request->keys()[$i])[0]=='weight' && explode('-', $request->request->keys()[$i])[1]==$id && explode('-', $request->request->keys()[$i])[2]==$num_of_set){
                    SetRep::create([
                        'idTrainings_Exercise' => $trainings_exercise->id,
                        'num_of_set' => $num_of_set,
                        'num_of_reps' => $request['reps-'.$id.'-'.$num_of_set],
                        'weight'=> $request['weight-'.$id.'-'.$num_of_set],
                    ]);
                    $num_of_set++;
                }
            }
        }
    }

    private function everything($user_equipments, $exercises, $user_experience, $user_type){

        $exercises = $this->generate_exercises('biceps', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises('triceps', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises('chest', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises('quads', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises('hamstrings', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises('calfs', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises('back', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises('abs', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises('shoulders', $user_equipments, $exercises, 1, $user_experience);        

        $final = $this->final($exercises);

        switch($user_type){
            case('bodybuilding'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 10);
                break;
            case('loose weight'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 20);
                break;
            case('endurance'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 20);
                break;
        }

        $data = array(
            'name' => 'Everything',
            'final'=> $final,
            'ex' => 'some',
            'cardio' => $cardio,
        );
        return $data;
    }

    private function upper_lower_split($name_of_last_training, $user_equipments, $exercises, $user_experience, $user_type){
        switch($name_of_last_training){
            case(null):

                $name = 'Upper body';
                $exercises = $this->generate_exercises('chest', $user_equipments, $exercises, 2, $user_experience);
                $exercises = $this->generate_exercises('back', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_primary_secundary('lower back', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises('shoulders', $user_equipments, $exercises, 2, $user_experience);
                $exercises = $this->generate_exercises('biceps', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises('triceps', $user_equipments, $exercises, 1, $user_experience);
                break;

            case('Upper body'):
                $name = 'Lower body';
                $exercises = $this->legs($user_equipments, $exercises, $user_experience);

                break;

            case('Lower body'):

                $name = 'Upper body';
                $exercises = $this->generate_exercises('chest', $user_equipments, $exercises,2, $user_experience);
                $exercises = $this->generate_exercises('back', $user_equipments, $exercises,1, $user_experience);
                $exercises = $this->generate_exercises_primary_secundary('lower back', $user_equipments, $exercises,1, $user_experience);
                $exercises = $this->generate_exercises('shoulders', $user_equipments, $exercises,2, $user_experience);
                $exercises = $this->generate_exercises('biceps', $user_equipments, $exercises,1, $user_experience);
                $exercises = $this->generate_exercises('triceps', $user_equipments, $exercises,1, $user_experience);

                break;
        }

        $final = $this->final($exercises);

        switch($user_type){
            case('bodybuilding'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 10);
                break;
            case('loose weight'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 20);
                break;
            case('endurance'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 20);
                break;
        }

        $data = array(
            'name' => $name,
            'final'=> $final,
            'ex' => 'some',
            'cardio' => $cardio,
        );

        return $data;

    }
    private function ppl_split($name_of_last_training, $user_equipments, $exercises, $user_experience){

        switch($name_of_last_training){
            case(null):
                $name = 'Push';
                $exercises = $this->push($user_equipments, $exercises, $user_experience);

                break;

            case('Push'):
                $name = 'Pull';
                $exercises = $this->pull($user_equipments, $exercises, $user_experience);

                break;

            case('Pull'):

                $name = 'Legs';
                $exercises = $this->legs($user_equipments, $exercises, $user_experience);
                break;

            case('Legs'):
                $name = 'Push';
                $exercises = $this->push($user_equipments, $exercises, $user_experience);

                break;
        }

        $final = $this->final($exercises);

        $data = array(
            'name' => $name,
            'final'=> $final,
            'ex' => 'some',
        );

        return $data;
    }


    private function ppal_split($name_of_last_training, $user_equipments, $exercises, $user_experience, $user_type){
        switch($name_of_last_training){
            case(null):
                $name = 'Push';
                $exercises = $this->push($user_equipments, $exercises, $user_experience);

                break;
            
            case('Push'):
                $name = 'Pull';
                $exercises = $this->pull($user_equipments, $exercises, $user_experience);

                break;

            case('Pull'):
                $name = 'Arms';

                //biceps
                $exercises = $this->generate_exercises_when('biceps', 'beginning', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('biceps', 'does not metter', $user_equipments, $exercises, 1, $user_experience);

                //triceps
                $exercises = $this->generate_exercises('triceps', $user_equipments, $exercises, 2, $user_experience);

                //shoulders
                $exercises = $this->generate_exercises_when('shoulders', 'beginning', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('shoulders', 'does not metter', $user_equipments, $exercises, 1, $user_experience);

                break;

            
            case('Arms'):
                $name = 'Legs';
                $exercises = $this->legs($user_equipments, $exercises, $user_experience);

                break;

            case('Legs'):
                $name = 'Push';
                $exercises = $this->push($user_equipments, $exercises, $user_experience);

                break;
        }
        $final = $this->final($exercises);

        switch($user_type){
            case('bodybuilding'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 7);
                break;
            case('loose weight'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 15);
                break;
            case('endurance'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 15);
                break;
        }

        $data = array(
            'name' => $name,
            'final'=> $final,
            'ex' => 'some',
            'cardio' => $cardio,
        );

        return $data;
    }


    private function five_day_split($name_of_last_training, $user_equipments, $exercises, $user_experience, $user_type){
        switch($name_of_last_training){
            case(null):
                $name = 'Chest and shoulders';

                //chest
                $exercises = $this->generate_exercises_when('chest', 'beginning', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('chest', 'does not metter', $user_equipments, $exercises, 2, $user_experience);
                $exercises = $this->generate_exercises_when('chest', 'end', $user_equipments, $exercises, 1, $user_experience);

                //shoulders
                $exercises = $this->generate_exercises_when('shoulders', 'beginning', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('shoulders', 'does not metter', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('shoulders', 'end', $user_equipments, $exercises, 1, $user_experience);

                break;

            case('Chest and shoulders'):
                $name = 'Quads and calfs';

                $exercises = $this->generate_exercises('calfs', $user_equipments, $exercises, 2, $user_experience);
                $exercises = $this->generate_exercises('quads', $user_equipments, $exercises, 4, $user_experience);

                break;

            case('Quads and calfs'):

                $name = 'Back';

                $exercises = $this->generate_exercises('abs', $user_equipments, $exercises, 2, $user_experience);

                $exercises = $this->generate_exercises_primary_secundary('lower back', $user_equipments, $exercises, 4, $user_experience);

                $exercises = $this->generate_exercises_when('abs', 'beginning', $user_equipments, $exercises, 2, $user_experience);
                $exercises = $this->generate_exercises_when('abs', 'does not metter', $user_equipments, $exercises, 2, $user_experience);

                break;

            case('Back'):
                $name = 'Hamstrings, glutes and calfs';
                
                $exercises = $this->generate_exercises('calfs', $user_equipments, $exercises, 2, $user_experience);
                $exercises = $this->generate_exercises('hamstrings', $user_equipments, $exercises, 4, $user_experience);
                $exercises = $this->generate_exercises('glutes', $user_equipments, $exercises, 1, $user_experience);

                break;

            case('Hamstrings, glutes and calfs'):

                $name = 'Arms';
                
                $exercises = $this->generate_exercises_when('biceps', 'beginning', $user_equipments, $exercises, 2, $user_experience);
                $exercises = $this->generate_exercises_when('biceps', 'does not metter', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('triceps', 'beginning', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('triceps', 'does not metter', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('triceps', 'end', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('shoulders', 'does not metter', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('shoulders', 'end', $user_equipments, $exercises, 1, $user_experience);

                break;

            case('Arms'):
                $name = 'Chest and shoulders';

                //chest
                $exercises = $this->generate_exercises_when('chest', 'beggining', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('chest', 'does not metter', $user_equipments, $exercises, 2, $user_experience);
                $exercises = $this->generate_exercises_when('chest', 'end', $user_equipments, $exercises, 1, $user_experience);

                //shoulders
                $exercises = $this->generate_exercises_when('shoulders', 'beggining', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('shoulders', 'does not metter', $user_equipments, $exercises, 1, $user_experience);
                $exercises = $this->generate_exercises_when('shoulders', 'end', $user_equipments, $exercises, 1, $user_experience);

                break;
        }

        $final = $this->final($exercises);

        switch($user_type){
            case('bodybuilding'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 5);
                break;
            case('loose weight'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 12);
                break;
            case('endurance'):
                $cardio = $this->generate_cardio('cardio', $user_equipments, 1, $user_experience, 12);
                break;
        }

        $data = array(
            'name' => $name,
            'final'=> $final,
            'ex' => 'some',
            'cardio' => $cardio,
        );

        return $data;
    }

    private function push($user_equipments, $exercises, $user_experience){
        //chest
        $exercises = $this->generate_exercises_when('chest', 'beginning', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises_when('chest', 'does not metter', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises_when('chest', 'end', $user_equipments, $exercises, 1, $user_experience);

        //shoulders
        $exercises = $this->generate_exercises_when('shoulders', 'beginning', $user_equipments, $exercises, 1, $user_experience);
        
        $exercises = $this->generate_exercises_when('shoulders', 'does not metter', $user_equipments, $exercises, 1, $user_experience);
        $exercises = $this->generate_exercises_when('shoulders', 'end', $user_equipments, $exercises, 1, $user_experience);

        //triceps
        $exercises = $this->generate_exercises('triceps', $user_equipments, $exercises, 2, $user_experience);

        return $exercises;
    }

    private function pull($user_equipments, $exercises, $user_experience){
        // abs
        $exercises = $this->generate_exercises('abs', $user_equipments, $exercises, 1, $user_experience);

        // lower back
        $exercises = $this->generate_exercises_primary_secundary('lower back', $user_equipments, $exercises, 1, $user_experience); 

        //back
        $exercises = $this->generate_exercises_when('back', 'beginning', $user_equipments, $exercises, 1, $user_experience);

        //lats
        $exercises = $this->generate_exercises_when('back', 'does not metter', $user_equipments,$exercises, 2, $user_experience);

        //biceps
        $exercises = $this->generate_exercises_when('biceps', 'beginning', $user_equipments,$exercises, 1, $user_experience);
        $exercises = $this->generate_exercises_when('biceps', 'does not metter', $user_equipments,$exercises, 1, $user_experience);

        return $exercises;
    }


    private function legs($user_equipments, $exercises, $user_experience){
        $exercises = $this->generate_exercises('calfs', $user_equipments, $exercises, 1, $user_experience);

        $exercises = $this->generate_exercises('glutes', $user_equipments, $exercises, 1, $user_experience);

        $exercises = $this->generate_exercises('quads', $user_equipments, $exercises, 3, $user_experience);

        $exercises = $this->generate_exercises('hamstrings', $user_equipments, $exercises, 2, $user_experience);

        return $exercises;
    }

    private function get_all_cardio($user_equipments){

        $idsExercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises.id','=','exercises_has_equipments.idExercise')
        ->join('usershasequipments','usershasequipments.idEquipment','=','exercises_has_equipments.idEquipment')
        ->join('when_to_exercise','when_to_exercise.id','=','exercises.idWhen_to_Exercise')
        ->select('exercises.id','exercises.name')
        ->where('when_to_exercise.name','cardio')
        ->distinct()
        ->orderBy('exercises.name')
        ->get();

        $ide = array();

        //pre kazdy cvik
        foreach($idsExercises as $id){

            $can = true;
            
            //vyberie equpments pre cvik
            $exercise_equipments = DB::table('exercises')
            ->join('exercises_has_equipments','exercises.id','=','exercises_has_equipments.idExercise')
            ->where('exercises.id','=',$id->id)
            ->orderBy('exercises.name','asc')
            ->get('exercises_has_equipments.idEquipment');
            
            //pre kazdy equipment pre cvik
            foreach($exercise_equipments as $idequip){
                if(!$user_equipments->contains($idequip)){
                    $can=false;
                }
            }

            if($can){
                $ide[] = [$id->id,$id->name];
            }
        }

        return $ide;
    }

    private function get_exercises($idsExercises, $user_equipments, $count, $user_experience){
        $ide = array();

        foreach($idsExercises as $id){
            $can = true;

            $exercise_equipments = DB::table('exercises')
            ->join('exercises_has_equipments','exercises.id','=','exercises_has_equipments.idExercise')
            ->where('exercises.id','=',$id->id)
            ->orderBy('exercises.idDifficulty')
            ->get('exercises_has_equipments.idEquipment');

            foreach($exercise_equipments as $idequip){
                if(!$user_equipments->contains($idequip)){
                    $can=false;
                }
            }

            if($can){
                $ide[] = [$id->id,$id->idDifficulty];
            }
        }

        $dif_1 = 0;
        $dif_2 = 0;
        $dif_3 = 0;

        for ($i=0; $i < count($ide) ; $i++) { 
            switch($ide[$i][1]){
                case(1):
                    $dif_1++;
                    break;
                case(2):
                    $dif_2++;
                    break;
                case(3):
                    $dif_3++;
                    break;
            }
        }

        switch($user_experience){
            case(1):
                if($dif_1>=$count){
                    for ($i=count($ide); $i>=$dif_1; $i--){
                        unset($ide[$i]);
                    }
                }
                else{
                    if ($dif_1+$dif_2>=$count) {
                        for ($i=count($ide); $i>=$dif_1+$dif_2; $i--){
                            unset($ide[$i]);
                        }
                    }
                }
                break;
            case(2):
                if ($dif_1+$dif_2>=$count) {
                    for ($i=count($ide); $i>=$dif_1+$dif_2; $i--){
                        unset($ide[$i]);
                    }
                }
                break;
        }

        shuffle($ide);

        return $ide;
    }

    private function generate_exercises($part, $user_equipments, $exercises, $count, $user_experience){
        $idsExercises = DB::table('exercises')
        ->join('part_exercised','exercises.idprimary_exercised','=','part_exercised.id')
        ->join('exercises_has_equipments','exercises.id','=','exercises_has_equipments.idExercise')
        ->join('usershasequipments','usershasequipments.idEquipment','=','exercises_has_equipments.idEquipment')
        ->select('exercises.id','exercises.idDifficulty')
        ->where('part_exercised.name', $part)
        ->distinct('exercises.id')
        ->orderBy('exercises.idDifficulty')
        ->get();

        $ide = $this->get_exercises($idsExercises, $user_equipments, $count,$user_experience);

        for($i = 0; $i<$count; $i++){
            $exercise = DB::table('exercises')
            ->where('id','=',$ide[$i])
            ->select('name','id')->get();
            $exercises->push($exercise[0]);
        }
        
        return $exercises;
    }

    private function generate_exercises_when($part, $when, $user_equipments, $exercises, $count, $user_experience){
        $idsExercises = DB::table('exercises')
        ->join('part_exercised','exercises.idprimary_exercised','=','part_exercised.id')
        ->join('exercises_has_equipments','exercises.id','=','exercises_has_equipments.idExercise')
        ->join('usershasequipments','usershasequipments.idEquipment','=','exercises_has_equipments.idEquipment')
        ->join('when_to_exercise','when_to_exercise.id','=','exercises.idWhen_to_Exercise')
        ->select('exercises.id','exercises.idDifficulty')
        ->where('part_exercised.name', $part)
        ->where('when_to_exercise.name',$when)
        ->distinct()
        ->orderBy('exercises.idDifficulty')
        ->get();

        $ide = $this->get_exercises($idsExercises, $user_equipments, $count, $user_experience);

        for($i = 0; $i<$count; $i++){
            $exercise = DB::table('exercises')
            ->where('id','=',$ide[$i])
            ->select('name','id')->get();
            $exercises->push($exercise[0]);
        }

        return $exercises;
    }

    private function generate_exercises_primary_secundary($part, $user_equipments, $exercises, $count, $user_experience){
        $idsExercises = DB::table('exercises')
        ->join('part_exercised as pe1','exercises.idprimary_exercised','=','pe1.id')
        ->join('part_exercised as pe2','exercises.idsecondary_exercised','=','pe2.id')
        ->join('exercises_has_equipments','exercises.id','=','exercises_has_equipments.idExercise')
        ->join('usershasequipments','usershasequipments.idEquipment','=','exercises_has_equipments.idEquipment')
        ->select('exercises.id','exercises.idDifficulty')
        ->where('pe1.name', $part)
        ->orWhere('pe2.name',$part)
        ->distinct()
        ->orderBy('exercises.idDifficulty')
        ->get();

        $ide = $this->get_exercises($idsExercises, $user_equipments, $count,$user_experience);

        for($i = 0; $i<$count; $i++){
            $exercise = DB::table('exercises')
            ->where('id','=',$ide[$i])
            ->select('name','id')->get();
            $exercises->push($exercise[0]);
        }

        return $exercises;
    }


    private function generate_cardio($when, $user_equipments, $count, $user_experience, $min){
        $idsExercises = DB::table('exercises')
        ->join('exercises_has_equipments','exercises.id','=','exercises_has_equipments.idExercise')
        ->join('usershasequipments','usershasequipments.idEquipment','=','exercises_has_equipments.idEquipment')
        ->join('when_to_exercise','when_to_exercise.id','=','exercises.idWhen_to_Exercise')
        ->select('exercises.id','exercises.idDifficulty')
        ->where('when_to_exercise.name',$when)
        ->distinct()
        ->orderBy('exercises.name')
        ->get();

        $ide = $this->get_exercises($idsExercises, $user_equipments, $count, $user_experience);

        $cardio = array();

        for($i = 0; $i<$count; $i++){
            $exercise = DB::table('exercises')
            ->where('id','=',$ide[$i])
            ->select('name','id')->get();
            $cardio[]=$exercise[0];
        }
        
        $final[] = ['is_cardio' => 'yes', 'id' => $cardio[0]->id, 'name' => $cardio[0]->name, 'min' => $min];
        return $final;
    }

    private function final($exercises){

        $user_type = DB::table('users_goals')
        ->join('types','types.id','=','users_goals.idType')
        ->where('idUser',Auth::user()->id)
        ->get('types.name')[0]->name;

        $final = array();

        $last_exercised = array();

        foreach($exercises as $exercise){
            $last_exercised[] = DB::table('trainings')
            ->join('trainings_exercises','trainings.id','=','trainings_exercises.idTraining')
            ->where('trainings.idUser', Auth::user()->id)
            ->where('trainings_exercises.idExercise',$exercise->id)
            ->latest('trainings.created_at')
            ->first(['trainings_exercises.id','trainings_exercises.idExercise']);                    
        }

        $offset = 0;

        for ($i=0; $i < count($exercises); $i++) {
            $final_exercises = array();
            $final_exercises[] = $exercises[$i]->name;
            if($last_exercised[$i]!=null){
                $sets[]=DB::table('set_reps')
                ->join('trainings_exercises','trainings_exercises.id','=','set_reps.idTrainings_Exercise')
                ->join('exercises','exercises.id','=','trainings_exercises.idExercise')
                ->where('trainings_exercises.id',$last_exercised[$i]->id)
                ->where('trainings_exercises.idExercise',$last_exercised[$i]->idExercise)
                ->select('trainings_exercises.id','name','trainings_exercises.idExercise','num_of_set','weight','num_of_reps')
                ->get();
                if(count($sets[$i-$offset])>3){
                    for($j=0; $j < count($sets[$i-$offset]); $j++){
                        $final_exercises[] = ['idExercise' => $sets[$i-$offset][$j]->idExercise, 'weight' => $sets[$i-$offset][$j]->weight, 'reps' => $sets[$i-$offset][$j]->num_of_reps, 'sets' => $j+1];
                    }
                }
                else{
                    $help[] = $this->get_sets($exercises[$i], $sets[$i-$offset]);
                    foreach($help[0] as $h){
                        $final_exercises[] = $h;
                    }
                }
            }
            else{
                $offset++;
                switch($user_type){
                    case('bodybuilding'):
                        for($j=0; $j < 4; $j++){
                            $final_exercises[] = ['idExercise' => $exercises[$i]->id, 'weight' => 0, 'reps' => 15-$j*2, 'sets' => $j+1];
                        }
                        break;
                    case('loose weight'):
                        for($j=0; $j < 4; $j++){
                            if($j == 0){
                                $final_exercises[] = ['idExercise' => $exercises[$i]->id, 'weight' => 0, 'reps' => 15, 'sets' => $j+1];
                            }
                            elseif($j == 1 || $j == 2){
                                $final_exercises[] = ['idExercise' => $exercises[$i]->id, 'weight' => 0, 'reps' => 12, 'sets' => $j+1];
                            }
                            else{
                                $final_exercises[] = ['idExercise' => $exercises[$i]->id, 'weight' => 0, 'reps' => 10, 'sets' => $j+1];
                            }
                        }
                        break;
                    case('endurance'):
                        for($j=0; $j < 4; $j++){
                            $r = 30 - ($j * 5);
                            if($r >= 15){
                                $final_exercises[] = ['idExercise' => $exercises[$i]->id, 'weight' => 0, 'reps' => $r, 'sets' => $j+1];
                            }
                            else{
                                $final_exercises[] = ['idExercise' => $exercises[$i]->id, 'weight' => 0, 'reps' => 15, 'sets' => $j+1];
                            }
                        }
                        break;
                }
                
            }
            $final[]=$final_exercises;
        }
        return $final;
    }

    private function get_sets($exercise, $set){
        $user_type = DB::table('users_goals')
        ->join('types','types.id','=','users_goals.idType')
        ->where('idUser',Auth::user()->id)
        ->get('types.name')[0]->name;

        switch(count($set)){
            case(1):
                $final_exercises[] = ['idExercise' => $set[0]->idExercise, 'weight' => $set[0]->weight, 'reps' => $set[0]->num_of_reps, 'sets' => 1];
                switch($user_type){
                    case('bodybuilding'):
                        for($j=1; $j < 4; $j++){
                            $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 15-$j*2, 'sets' => $j+1];
                        }
                        break;
                    case('loose weight'):
                        for($j=1; $j < 4; $j++){
                            if($j == 1 || $j == 2){
                                $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 12, 'sets' => $j+1];
                            }
                            else{
                                $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 10, 'sets' => $j+1];
                            }
                        }
                        break;
                    case('endurance'):
                        for($j=0; $j < 4; $j++){
                            $r = 30 - ($j * 5);
                            if($r >= 15){
                                $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => $r, 'sets' => $j+1];
                            }
                            else{
                                $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 15, 'sets' => $j+1];
                            }
                        }
                        break;
                }
                
                break;
            case(2):
                for($j=0; $j < count($set); $j++){
                    $final_exercises[] = ['idExercise' => $set[$j]->idExercise, 'weight' => $set[$j]->weight, 'reps' => $set[$j]->num_of_reps, 'sets' => $j+1];
                }
                switch($user_type){
                    case('bodybuilding'):
                        for($j=2; $j < 4; $j++){
                            $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 15-$j*2, 'sets' => $j+1];
                        }
                        break;
                    case('loose weight'):
                        for($j=2; $j < 4; $j++){
                            if($j == 2){
                                $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 12, 'sets' => $j+1];
                            }
                            else{
                                $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 10, 'sets' => $j+1];
                            }
                        }
                        break;
                    case('endurance'):
                        for($j=2; $j < 4; $j++){
                            $r = 30 - ($j * 5);
                            if($r >= 15){
                                $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => $r, 'sets' => $j+1];
                            }
                            else{
                                $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 15, 'sets' => $j+1];
                            }
                        }
                        break;
                }

                break;
            case(3):
                for($j=0; $j < count($set); $j++){
                    $final_exercises[] = ['idExercise' => $set[$j]->idExercise, 'weight' => $set[$j]->weight, 'reps' => $set[$j]->num_of_reps, 'sets' => $j+1];
                }
                switch($user_type){
                    case('bodybuilding'):
                        $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 9, 'sets' => $j+1];
                        break;
                    case('loose weight'):
                        $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 10, 'sets' => $j+1];
                        break;
                    case('endurance'):
                        $final_exercises[] = ['idExercise' => $exercise->id, 'weight' => $set[0]->weight, 'reps' => 15, 'sets' => $j+1];
                        break;
                }
                break;
        }
        return $final_exercises;
    }
}