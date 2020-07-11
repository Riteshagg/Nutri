<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\notification\emailNotification;
use App\OnlineAppointment;
use App\User;
use Illuminate\Http\Request;
use App\Nutritionist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class NutritionistController extends Controller
{
    //


    public function index( Request $request)
    {

        $user_list_array = User::role('Nutritionist')->orderBy('id','DESC')->paginate(10);

        return view('pages.nutritionist_list', ['user_list' => $user_list_array ])->with('i', ($request->input('page', 1) - 1) * 10);

    }

    public function create()
    {
        return view('pages.nutritionist_add');
    }

    public function save_user(Request $request){

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required'
        ]);

        $password=Hash::make('123456');

        $users_detail = [
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=>$password,
            'phone'=>$request->input('phone'),
            'dob'=>$request->input('dob'),
            'created_at'=>now(),
        ];


        $user = User::create($users_detail);
        $user->assignRole('Nutritionist');
        $details = [
            'greeting' => 'Hi '. $request->input('name'),
            'body' => 'Welcome to NutriSolution, <br/>Your Application Password : 123456',
            'thanks' => 'Thank you for using NutriSolution.com !',
            'actionText' => 'Visit now',
            'actionURL' => url('/'),
        ];

       $mail = Mail::to($user->email)->send(new WelcomeMail($details));


        if($user){
            return redirect()->route('nutritionist_add')->with('success','Nutritionist created successfully !');
        }else{
            return redirect()->route('nutritionist_add')->with('error','Error !');

        }

    }

    public function ajaxNut(Request $request){
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
        $user = User::where( 'id','!=',$request['id'])->where('email',$request['email'])->get();
        if(count($user)>0){
            return redirect()->route('nutritionist_list')->with('error', 'Email id is already exist!');
        }else {
            $user = User::findOrFail($request['id']);
            $data = $user->fill([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'dob' => $request['dob'],
            ])->save();
            return redirect()->route('nutritionist_list')->with('success', 'Nutritionist updated successfully !');
        }
    }


    public function delete($id){

            $user = User::find($id);
            $user->removeRole("Nutritionist");
            $user->delete();
            $removeUser = User::destroy($id);
            $getNut = User::role('Nutritionist')->select('id')->orderBy('id','DESC')->first();

            if($getNut->id!=""){
                $users_to_nutritionist = Nutritionist::where('nutritionistId', $id)->update(['nutritionistId'=>$getNut->id]);
                $appointment = Appointment::where('nutritionistId',$id)->update(['nutritionistId'=>$getNut->id]);
                $online_appointment = OnlineAppointment::where('nutritionistId',$id)->update(['nutritionistId'=>$getNut->id]);
            }


            if($user){
                return redirect()->route('nutritionist_list')->with('success','Nutritionist deleted successfully !');
            }else{
                return redirect()->route('nutritionist_list')->with('error','Error !');
            }



    }



}
