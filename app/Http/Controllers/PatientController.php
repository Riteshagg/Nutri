<?php
namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\notification\emailNotification;
use App\Trainer;
use App\User;
use App\Nutritionist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class PatientController extends Controller
{

    public function index(Request $request)
    {

        $data = $nutritionists = $selectUserToAssign = $filter = array();

        if(Auth::user()->hasRole('Admin')){
            $data = Nutritionist::with('user')->orderBy('userId','DESC')->paginate(10);
        }

        elseif(Auth::user()->hasRole('Nutritionist')){
            $nutritionist = Auth::id();
            $data = Nutritionist::with('user')->where('nutritionistId', $nutritionist)->orderBy('userId','DESC')->paginate(10);
        }

        if(Auth::user()->hasRole('Admin'))
            $nutritionists  = User::role('Nutritionist')->orderBy('name','DESC')->get();


        return view('pages.patientlist', compact('data', 'nutritionists','selectUserToAssign','filter'))->with('i', ($request->input('page', 1) - 1) * 10);

    }

    public function create()
    {
        $nutritionist_list = array();

        if(Auth::user()->hasRole('Admin'))
            $nutritionist_list  = User::role('Nutritionist')->orderBy('name','DESC')->get();

        return view('pages.patientadd', ['nutritionists' => $nutritionist_list ]);
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'nutritionistSelect'=> 'required'
        ]);
            $pass_str = Str::random(12);
            $password = Hash::make($pass_str);

            $users_detail = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $password,
                'phone' => $request->input('phone'),
                'dob' => $request->input('dob'),
                'created_at' => now(),
            ];

            $user = User::create($users_detail);
            $user->assignRole('Patient');


            $Nutritionist = Nutritionist::create([
                'userId' => $user->id,
                'nutritionistId' => $request->input('nutritionistSelect')
            ]);

            $details = [
                'greeting' => 'Hi ' . $request->input('name'),
                'body' => 'Welcome to NutriSolution, <br/> Your Application Password : ' . $pass_str,
                'thanks' => 'Thank you for using NutriSolution.com !',
                'actionText' => 'Visit now',
                'actionURL' => url('/'),
            ];
            $mail = Mail::to($user->email)->send(new WelcomeMail($details));

        if($user){
            return redirect()->route('patientadd')->with('success','Cliente inserido com sucesso!');
        }else{
            return redirect()->route('patientadd')->with('error','Error !');

        }
    }

    public function ajaxHandler(Request $request)
    {
        $user = User::find($request->input('id'));
        $nutritionistDetails = Nutritionist::find($request['id'])->nutritionistDetails;
        $user->nutritionistId = $nutritionistDetails->id;
        return $user;
    }

    public function update(Request $request){


        $this->validate($request,[
            'name' => 'required|string|max:255',
            'phone'=>'required',
            'dob'=>'required|date',
            'status'=>'required',
            'nutritionistSelect'=>'required',
            'email' => 'required|email',
        ]);
        $user = User::where( 'id','!=',$request['id'])->where('email',$request['email'])->get();
       if(count($user)>0){
           return redirect()->route('patientlist')->with('error', 'Email id is already exist!');
       }else {
           $user = User::findOrFail($request['id']);
           $data = $user->fill([
               'name' => $request['name'],
               'email' => $request['email'],
               'phone' => $request['phone'],
               'dob' => $request['dob'],
               'status' => $request['status'],
           ])->save();

           $assign = Nutritionist::where('userId', $request['id'])->update([
               'nutritionistId' => $request['nutritionistSelect'],
               'updated_at' => new \DateTime(),

           ]);


           return redirect()->route('patientlist')->with('success', 'Cliente actualizado com sucesso !');
       }


    }

    public function delete($id){
        $user = User::destroy($id);
        $users_to_nutritionist = Nutritionist::where('userId', $id)->delete();
        if($user && $users_to_nutritionist ){
            return redirect()->route('patientlist')->with('success','Cliente Apagado !');
        }else{
            return redirect()->route('patientlist')->with('error','Cliente não apagado !');
        }
    }


    public function trainerCreate()
    {
        $trainer_list = array();

        if(Auth::user()->hasRole('Admin'))
            $trainer_list  = User::role('Personal_trainer')->orderBy('name','DESC')->get();

        return view('pages.trainersPatientAdd', ['trainers' => $trainer_list ]);
    }




    public function trainerPatientsStore(Request $request)
    {

       $this->validate($request, [
            'name' => 'required',
           // 'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'trainerSelect'=> 'required'
        ]);

        $user= User::where('email', $request->input('email'))->first();
        if($user != null){
            $user->assignRole('Patient');
            $trainerToUser= Trainer::create([
                'userId' => $user->id,
                'trainerId' => $request->input('trainerSelect')
            ]);
        }else {
            $pass_str = Str::random(12);
            $password = Hash::make($pass_str);

            $users_detail = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $password,
                'phone' => $request->input('phone'),
                'dob' => $request->input('dob'),
                'created_at' => now(),
            ];


            $user = User::create($users_detail);
            $user->assignRole('Patient');

            $trainer = Trainer::create([
                'userId' => $user->id,
                'trainerId' => $request->input('trainerSelect')
            ]);
            $details = [
                'greeting' => 'Hi ' . $request->input('name'),
                'body' => 'Welcome to NutriSolution, \n Your Application Password : ' . $pass_str,
                'thanks' => 'Thank you for using NutriSolution.com !',
                'actionText' => 'Visit now',
                'actionURL' => url('/'),
            ];
            $mail = Mail::to($user->email)->send(new WelcomeMail($details));
        }
        if($user){
            return redirect()->route('trainerPatientAdd')->with('success','Cliente inserido com sucesso!');
        }else{
            return redirect()->route('trainerPatientAdd')->with('error','Error !');

        }
    }


    public function trainerPatientList(Request $request)
    {
        $data = $trainers = array();

        if(Auth::user()->hasRole('Admin')){
            $data = Trainer::with('user')->orderBy('userId','DESC')->paginate(10);
        }

        elseif(Auth::user()->hasRole('Personal_trainer')){
            $trainer = Auth::id();
            $data = Trainer::with('user')->where('trainerId', $trainer)->orderBy('userId','DESC')->paginate(10);
        }

        if(Auth::user()->hasRole('Admin'))
            $trainers  = User::role('Personal_trainer')->orderBy('name','DESC')->get();


        return view('pages.trainerPatientList', compact('data', 'trainers'))->with('i', ($request->input('page', 1) - 1) * 10);

    }

    public function ajaxTrainerPatients(Request $request)
    {
        $user = User::find($request->input('id'));
        $trainerDetails = Trainer::find($request['id'])->trainerDetails;
        $user->trainerId = $trainerDetails->id;
        return $user;
    }




    public function trainerPatientsUpdate(Request $request){

        $this->validate($request,[
            'name' => 'required|string|max:255',
            'phone'=>'required',
            'dob'=>'required|date',
            'status'=>'required',
            'trainerSelect'=>'required',
            'email' => 'required|email',
        ]);

        $user = User::where( 'id','!=',$request['id'])->where('email',$request['email'])->get();
        if(count($user)>0){
            return redirect()->route('trainer_patient_list')->with('error', 'Email id is already exist!');
        }else {
            $user = User::findOrFail($request['id']);
            $data = $user->fill([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'dob' => $request['dob'],
                'status' => $request['status'],
            ])->save();

            Trainer::where('userId', $request['id'])->update([
                'trainerId' => $request['trainerSelect'],
                'updated_at' => new \DateTime(),

            ]);
            return redirect()->route('trainer_patient_list')->with('success', 'Cliente actualizado com sucesso !');
        }

    }


    public function trainerPatientsDelete($id){
        $user = User::destroy($id);
        $users_to_trainer = Trainer::where('userId', $id)->delete();
        if($user && $users_to_trainer ){
            if(Auth::user()->hasRole('Admin')){
                return redirect()->route('patientlist')->with('success','Cliente Apagado !');
            }else{
                return redirect()->route('trainer_patient_list')->with('success','Cliente Apagado !');
            }

        }else{
            return redirect()->route('trainer_patient_list')->with('error','Cliente não apagado !');
        }
    }




    public function filterTrainerNutri(Request $request){
        $data = $selectUserToAssign = array();
        $filter = $request->input('selectTrainerOrNutritionist');
        if($filter == 'trainer'){


            if(Auth::user()->hasRole('Admin')){
                $data = Trainer::with('user')->orderBy('userId','DESC')->get();
            }

            elseif(Auth::user()->hasRole('Personal_trainer')){
                $selectUserToAssign = Auth::id();
                $data = Trainer::with('user')->where('trainerId', $selectUserToAssign)->orderBy('userId','DESC')->get();
            }

            if(Auth::user()->hasRole('Admin'))
                $selectUserToAssign  = User::role('Personal_trainer')->orderBy('name','DESC')->get();

        }else if($filter == 'nutritionist'){

            if(Auth::user()->hasRole('Admin')){
                $data = Nutritionist::with('user')->orderBy('userId','DESC')->get();
            }

            elseif(Auth::user()->hasRole('Nutritionist')){
                $selectUserToAssign = Auth::id();
                $data = Nutritionist::with('user')->where('nutritionistId', $selectUserToAssign)->orderBy('userId','DESC')->get();
            }

            if(Auth::user()->hasRole('Admin'))
                $selectUserToAssign  = User::role('Nutritionist')->orderBy('name','DESC')->get();


             }


        return view('pages.patientlist', compact('data', 'selectUserToAssign','filter'))->with('i', ($request->input('page', 1) - 1) * 10);





    }


}
