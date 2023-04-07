<?php

namespace App\Http\Controllers;

use App\Models\Days_per_week;
use App\Models\Height;
use App\Models\UsershasEquipments;
use App\Models\Users_goal;
use App\Models\Weight;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileController extends Controller
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

        $name=DB::table('users')
        ->where('id', $id)
        ->pluck('name');

        $weight = DB::table('weights')
        ->where('idUser', $id)
        ->latest('created_at')
        ->pluck('weight')
        ->first();

        $height = DB::table('heights')
        ->where('idUser', $id)
        ->latest('created_at')
        ->pluck('height')
        ->first();

        $days = DB::table('days_per_weeks')
        ->where('idUser', $id)
        ->latest('created_at')
        ->pluck('days')
        ->first();

        $reasons = DB::table('reasons')
        ->get(['id','name']);

        $experience = DB::table('users')
        ->join('experiences','users.idExperience','=','experiences.id')
        ->where('users.id',$id)
        ->pluck('experiences.name')
        ->first();

        $equipments = DB::table('equipments')
        ->where('name','!=','body')
        ->get(['id','name']);

        $experiences = DB::table('experiences')
        ->get(['id','name']);

        $goal = DB::table('users')
        ->join('users_goals','users.id','=','users_goals.idUser')
        ->join('types','types.id','=','users_goals.idType')
        ->where('users.id',$id)
        ->latest('users_goals.created_at')
        ->first('types.name');

        $goals = DB::table('types')
        ->whereNot('name','stretching')
        ->whereNot('name','cardio')
        ->get(['id','name']);

        $idn_goal = DB::table('users_goals')
        ->where('idUser',$id)
        ->latest('created_at')
        ->first('ind-goal as ind_goal');

        $data = array(
            'name' => $name,
            'weight' => $weight,
            'height' => $height,
            'days' => $days,
            'reasons' => $reasons,
            'experience' => $experience,
            'experiences' => $experiences,
            'equipments' => $equipments,
            'goal' => $goal,
            'goals' => $goals,
            'ind_goal' => $idn_goal,
        );


        // return dd($data);
        return view('/profile')->with($data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function changeemail(Request $request):RedirectResponse
    {
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ],$massage = ['email'=>"The email has already been taken."]);

        if($validator->fails()){
            return redirect('/profile')->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        DB::table('users')->where('id',Auth::user()->id)->update(['email'=>$request['email'],'updated_at'=>Carbon::now()->toDateTimeString()]);

        return redirect('/profile');
    }

    public function changeheight(Request $request){
        
        Height::create([
            'idUser' => Auth::user()->id,
            'height' => $request['height'],
        ]);

        return redirect('/profile');
    }

    public function changeweight(Request $request){
        
        Weight::create([
            'idUser' => Auth::user()->id,
            'weight' => $request['weight'],
        ]);

        return redirect('/profile');
    }

    public function changedays(Request $request){
        
        DB::table('days_per_weeks')->where('idUser',Auth::user()->id)->update(['days'=>$request['days'],'idReason'=>$request['idreason'],'updated_at'=>Carbon::now()->toDateTimeString()]);

        return redirect('/profile');
    }

    public function changename(Request $request){
        
        DB::table('users')->where('id',Auth::user()->id)->update(['name'=>$request['name'],'updated_at'=>Carbon::now()->toDateTimeString()]);

        return redirect('/profile');
    }

    public function deletename(){
        
        DB::table('users')->where('id',Auth::user()->id)->update(['name'=>null,'updated_at'=>Carbon::now()->toDateTimeString()]);

        return redirect('/profile');
    }

    public function changeexperience(Request $request){
        
        DB::table('users')->where('id',Auth::user()->id)->update(['idExperience'=>$request['idexperience'],'updated_at'=>Carbon::now()->toDateTimeString()]);

        return redirect('/profile');
    }

    public function changeequipments(Request $request){

        DB::table('usershasequipments')->where('idUser',Auth::user()->id)->delete();

        $body = DB::table('equipments')
        ->where('name','body')
        ->get('id');

        UsershasEquipments::create([
            'idUser' => Auth::user()->id,
            'idEquipment' => $body[0]->id,
        ]);

        foreach($request['equipments'] as $equipment){
            // return dd($equipment);
            UsershasEquipments::create([
                'idUser' => Auth::user()->id,
                'idEquipment' => $equipment,
            ]);
        }

        return redirect('/profile');
    }

    public function changegoal(Request $request){

        if($request['ind-goal']!=null){
            DB::table('users_goals')->where('idUser',Auth::user()->id)->update(['idType' => $request['idgoal'], 'ind-goal' => $request['ind-goal']]);
        }
        else{
            DB::table('users_goals')->where('idUser',Auth::user()->id)->update(['idType' => $request['idgoal']]);
        }

        return redirect('/profile');
    }


    public function deleteindgoal(Request $request){
        DB::table('users_goals')->where('idUser',Auth::user()->id)->update(['ind-goal' => null]);

        return redirect('/profile');
    }

    public function addindgoal(Request $request){

        DB::table('users_goals')->where('idUser',Auth::user()->id)->update(['ind-goal' => $request['ind-goal']]);

        return redirect('/profile');
    }
}