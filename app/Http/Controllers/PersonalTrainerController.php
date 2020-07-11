<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Mail\WelcomeMail;
use App\OnGoingPlan;
use App\OnlineAppointment;
use App\Trainer;
use App\User;
use Illuminate\Http\Request;
use App\Nutritionist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class PersonalTrainerController extends Controller
{
    public function index( Request $request)
    {

        $user_list_array = User::role('Personal_trainer')->orderBy('id','DESC')->paginate(10);

        return view('pages.personal_trainer_list', ['user_list' => $user_list_array ])->with('i', ($request->input('page', 1) - 1) * 10);

    }

    function randomNumber($length) {
        $result = '';

        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }

    public function save_user(Request $request){


        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required'
        ]);

        $passRandom = $this->randomNumber(8);
        $password=Hash::make($passRandom);

        $users_detail = [
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=>$password,
            'phone'=>$request->input('phone'),
            'dob'=>$request->input('dob'),
            'created_at'=>now(),
        ];


        $user = User::create($users_detail);
        $user->assignRole('Personal_trainer');
        $details = [
            'greeting' => 'Hi '. $request->input('name'),
            'body' => 'Welcome to NutriSolution, <br/>Your Application Password :'.$passRandom,
            'thanks' => 'Thank you for using NutriSolution.com !',
            'actionText' => 'Visit now',
            'actionURL' => url('/'),
        ];

        $mail = Mail::to($user->email)->send(new WelcomeMail($details));
        if($user){
            return redirect()->route('personal_trainer_add')->with('success','personal trainer created successfully !');
        }else{
            return redirect()->route('personal_trainer_add')->with('error','Error !');

        }

    }

    public function create()
    {
        return view('pages.personal-trainer-add');
    }


    public function ajaxPersonalTrainer(Request $request){
        $user = User::where("id",$request->input('id'))->get();
        return $user;
    }


    public function update(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'phone'=>'required',
            'dob'=>'required|date',
            'email' => 'required|email',
        ]);
        $user = User::findOrFail($request['id']);
        $user1 = User::where( 'id','!=',$request['id'])->where('email',$request['email'])->get();
        if(count($user1)>0){
            return redirect()->route('personal_trainer_list')->with('error', 'Email id is already exist!');
        }else {
            $data = $user->fill([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'dob' => $request['dob'],
            ])->save();
            return redirect()->route('personal_trainer_list')->with('success', 'Personal trainer updated successfully !');
        }
    }


    public function delete($id){


        $user = User::find($id);
        $user->removeRole("Personal_trainer");
        $user->delete();
        $removeUser = User::destroy($id);
        $getTrainer = User::role('Personal_trainer')->select('id')->orderBy('id','DESC')->first();

        if($getTrainer->id !=""){
            $users_to_trainer = Trainer::where('trainerId', $id)->update(['trainerId'=>$getTrainer->id]);
            $online_appointment = OnGoingPlan::where('trainerId',$id)->update(['trainerId'=>$getTrainer]);
        }


        if($user){
            return redirect()->route('personal_trainer_list')->with('success','Personal trainer deleted successfully !');
        }else{
            return redirect()->route('personal_trainer_list')->with('error','Error !');
        }














        $user = User::destroy($id);
        if($user ){
            return redirect()->route('personal_trainer_list')->with('success','Personal trainer deleted successfully !');
        }else{
            return redirect()->route('personal_trainer_list')->with('error','Error !');
        }
    }


}
