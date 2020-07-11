<?php

namespace App\Http\Controllers;

use App\OnGoingPlan;
use Illuminate\Http\Request;
use App\User;
use App\Nutritionist;
use App\Appointment;
use App\OnlineAppointment;
use App\Trainer;

use Illuminate\Support\Facades\Auth;

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
        $patient = $appointment = $onlineappointments=$ongoingPlan = array();
        $nutritionist = array(
            'total' => User::role('Nutritionist')->count(),
            'active' => User::role('Nutritionist')->where('status', '1')->count(),
        );

        if (Auth::user()->hasRole('Admin')) :
            $patient = array(
                'total' => User::role('Patient')->count(),
                'active' => User::role('Patient')->where('status', '1')->count(),
            );
        elseif (Auth::user()->hasRole('Nutritionist')):
            $patient = array(
                'active' => Nutritionist::with('user')->where('nutritionistId', Auth::id())->count(),
            );
        endif;

        if (Auth::user()->hasRole('Admin')) :
            $appointment = array(
                'active' => Appointment::count(),
            );
        elseif (Auth::user()->hasRole('Nutritionist')):
            $appointment = array(
                'active' => Appointment::where('nutritionistId', Auth::id())->count(),
            );
        elseif (Auth::user()->hasRole('Patient')):
            $user_details = User::find(Auth::id());
            $appointments = Appointment::where('patientId', Auth::id())->get();
            $onlineappointments = OnlineAppointment::where('patientId', Auth::id())->get();
            $nutritionistDetails = '';
            $getQuery = Nutritionist::where('userId', Auth::id())->first();
            if ($getQuery) {
                $nutritionistDetails = Nutritionist::find(Auth::id())->nutritionistDetails;
            }
            $ongoingPlan = OnGoingPlan::where('patientId', Auth::id())->get();
            $getQuery1 = Trainer::where('userId', Auth::id())->first();
            $trainerDetails = '';
            if ($getQuery1){
                $trainerDetails = Trainer::find(Auth::id())->trainerDetails;
        }


        endif;

        if(Auth::user()->hasRole('Patient')):

                $findNutritionist  = Nutritionist::where("userId", Auth::id())->first();
                if($findNutritionist && $findNutritionist->nutritionistId){
                    return view('pages.patient_dashboard',  compact('user_details','trainerDetails','ongoingPlan','nutritionistDetails',  'appointments', 'onlineappointments'));
                }else{
                    session()->flash('success', 'Nutritionist not assigned');
                    return view('pages.patient_dashboard',  compact('user_details','trainerDetails','ongoingPlan','nutritionistDetails',  'appointments', 'onlineappointments'));
                }
        else:
            return view('dashboard',  compact('nutritionist', 'patient', 'appointment'));
        endif;
    }

    public function download(Request $request,$id){

        $file= storage_path(). "/appointment/".$id;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($file, $id, $headers);
    }

    public function downloadOnline(Request $request,$id){

        $file= storage_path(). "/onlineAppointment/".$id;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($file, $id, $headers);
    }

    public function trainerDownloadOnline(Request $request,$id){

        $file= storage_path(). "/onGoingTrainingPlan/".$id;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($file, $id, $headers);
    }


}
