<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usershasequipments;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Height;
use App\Models\Weight;
use App\Models\Users_goal;
use App\Models\Days_per_week;
use App\Models\User_has_Equipment;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['max:255'],
            'surname' => ['max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'idGender' => $data['idgender'],
            'age' => $data['age'],
            'idExperience' => $data['idexperience'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $height = Height::create([
            'idUser' => $user->id,
            'height' => $data['height'],
        ]);

        $weight = Weight::create([
            'idUser' => $user->id,
            'weight' => $data['weight'],
        ]);

        $user_goal = Users_goal::create([
            'idType' => $data['idgoal'],
            'idUser' => $user->id,
            'ind-goal' => $data['ind-goal'],
        ]);

        $days = Days_per_week::create([
            'idUser' => $user->id,
            'days' => $data['days_per_week'],
        ]);

        $body = DB::table('equipments')
        ->where('name','body')
        ->get('id');

        Usershasequipments::create([
            'idUser' => $user->id,
            'idEquipment' => $body[0]->id,
        ]);

        foreach($data['equipments'] as $equipment){
            $eq_id = DB::table('equipments')
            ->where('name', $equipment)
            ->get('id');

            // return dd($equipment);
            UsershasEquipments::create([
                'idUser' => $user->id,
                'idEquipment' => $eq_id[0]->id,
            ]);
        }

        return $user;
    }
}
