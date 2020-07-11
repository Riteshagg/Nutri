<?php
namespace App\Http\Controllers;

use App\Nutritionist;
use App\User;
use App\Appointment;
use App\OnlineAppointment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function GuzzleHttp\Promise\all;


class AppointmentController extends Controller
{

    public function onlineAppointment(Request $request)
    {

        $nutritionists = $patient_list = $onappointment = $queries = array();

        if (Auth::user()->hasRole('Admin')) :
            $nutritionists = User::role('Nutritionist')->orderBy('id', 'DESC')->paginate();

        endif;

        if (Auth::user()->hasRole('Admin')) :
            $selectedNutritionist = trim($request->input('nid'));
        elseif (Auth::user()->hasRole('Nutritionist')):
            $selectedNutritionist = Auth::id();
        endif;

        $patients = Nutritionist::with('user')->where('nutritionistId', $selectedNutritionist)->get();

        $uid = $request->input('uid');

        if (isset($uid)) {
                $onappointment_query = OnlineAppointment::where('patientId', $uid);
                $query = OnlineAppointment::where('patientId', $uid)->orderBy('id', 'DESC');


            if ($onappointment_query->exists()) {
                $onappointment = $onappointment_query->get();
            }
            else {
                $onappointment = array();
            }

            if( $query != ""){
                $queries = $query->first();
            }else{
                $queries = '';
            }


        }

        return view('pages.onlineappointment', compact('nutritionists', 'patients', 'onappointment','queries'));

    }










    public function appointment(Request $request)
    {

        $nutritionists = $patient_list = $appointmentlist = array();

        if (Auth::user()->hasRole('Admin')) :
            $nutritionists = User::role('Nutritionist')->orderBy('id', 'DESC')->paginate();
        endif;

        if (Auth::user()->hasRole('Admin')) :
            $selectedNutritionist = trim($request->input('nid'));
        elseif (Auth::user()->hasRole('Nutritionist')):
            $selectedNutritionist = Auth::id();
        endif;

        $patients = Nutritionist::with('user')->where('nutritionistId', $selectedNutritionist)->get();

        $uid = $request->input('uid');

        if (isset($uid)) {
            $appointment_query = Appointment::where('patientId', $uid);
            if ($appointment_query->exists())
                $appointmentlist = $appointment_query->get();
            else
                $appointmentlist = array();
        }

        return view('pages.appointment', compact('nutritionists', 'patients', 'appointmentlist'));

    }

    public function appointment_store(Request $request)
    {
        $this->validate($request, [
            'appDate' => 'required',
            'weight' => 'required',
            'mm' => 'required',
            'fm' => 'required',
            'tw' => 'required',
            'vf' => 'required',
            'hw' => 'required',
        ]);

        $appDate = trim($request->input('appDate'));
        $weight = trim(str_replace(",",".",$request->input('weight')));
        $muscleMass = trim(str_replace(",",".",$request->input('mm')));
        $fatMass = trim(str_replace(",",".",$request->input('fm')));
        $visceralFat = trim(str_replace(",",".",$request->input('vf')));
        $totalWater = trim(str_replace(",",".",$request->input('tw')));
        $hwRatio = trim(str_replace(",",".",$request->input('hw')));
        $patientID = trim(str_replace(",",".",$request->input('uid')));

        $fileName1 = time().'.'.$request->attachments->extension();
        $request->attachments->move(storage_path('appointment'), $fileName1);

        $fileName2 = time().'_2.'.$request->attachments1->extension();
        $request->attachments1->move(storage_path('appointment'), $fileName2);

//        die(print_r($fileName2));

        if (Auth::user()->hasRole('Admin')){
            $nutritionistID = trim($request->input('nid'));
        } else { //set the session by default when its a nutritionist
            $nutritionistID = Auth::id();
        }

        $appointment_detail = [
            'patientId'=>$patientID,
            'nutritionistId'=>$nutritionistID,
            'weight'=> $weight,
            'muscleMass'=>$muscleMass,
            'fatMass'=>$fatMass,
            'totalWater'=>$totalWater,
            'visceralFat'=>$visceralFat,
            'hipWaistRatio'=>$hwRatio,
            'attachments'=>json_encode(['attach1'=>$fileName1,'attach2'=>$fileName2]),
            'date'=>$appDate,
        ];

        $appointment = Appointment::create($appointment_detail);

        if($appointment){
            return redirect()->route('appointments')->with('success','Appointment Added successfully !');
        }else{
            return redirect()->route('appointments')->with('error','Error !');

        }


    }

    public function ajaxHandler(Request $request){
        $appointment = Appointment::find($request->input('id'));

        return $appointment;
    }


    public function appointUpdate(Request $request){

        $this->validate($request, [
            'sAppDate' => 'required',
            'sAppWeight' => 'required',
            'sAppmm' => 'required',
            'sAppfm' => 'required',
            'sApptw' => 'required',
            'sApphw' => 'required',
            'sAppvf' => 'required',
            'mattach'=>'nullable',
            'mattach2'=>'nullable'
        ]);
        $fileName1 = '';
        if($request->hasFile('mattach'))
        {

            $fileName1 = time().'.'.$request->mattach->extension();
            $request->mattach->move(storage_path('appointment'), $fileName1);
        }

        $fileName2= '';
        if($request->hasFile('mattach2'))
        {
            $fileName2 = time().'_2.'.$request->mattach2->extension();
            $request->mattach2->move(storage_path('appointment'), $fileName2);
        }

        if($fileName1 != '' && $fileName2 !='') {
            $appointment = Appointment::where('id', $request->input('sAppId1'))->update([
                'weight' => $request['sAppWeight'],
                'muscleMass' => $request['sAppmm'],
                'fatMass' => $request['sAppfm'],
                'totalWater' => $request['sApptw'],
                'visceralFat' => $request['sAppvf'],
                'hipWaistRatio' => $request['sApphw'],
                'date' => $request['sAppDate'],
                'attachments' => json_encode(['attach1'=>$fileName1,'attach2'=>$fileName2]),

            ]);
        }else{
            $appointment = Appointment::where('id', $request->input('sAppId1'))->update([
                'weight' => $request['sAppWeight'],
                'muscleMass' => $request['sAppmm'],
                'fatMass' => $request['sAppfm'],
                'totalWater' => $request['sApptw'],
                'visceralFat' => $request['sAppvf'],
                'hipWaistRatio' => $request['sApphw'],
                'date' => $request['sAppDate'],

            ]);
        }
        return redirect()->route('appointments')->with('success','Appointment updated successfully !');
    }

    public function ajaxDelete(Request $request){
        $user = Appointment::destroy( $request->input('id'));
        return $user;
    }



    public function SendAjaxJson($request){
        $objectives = [
            "weight_target"=>$request['weight_target'],
            "weight_target_kg"=>$request['weight_target_kg'],
            "weight_max"=>$request['weight_max'],
            "weight_min"=>$request['weight_min'],
            "weight_desired"=>$request['weight_desired'],
            "weight_history"=>$request['weight_history'],
            "weight_max_date"=>$request['weight_max_date'],
            "weight_min_date"=>$request['weight_min_date'],
            "weight_desired_date"=>$request['weight_desired_date'],
            "muscle_mass"=>$request['muscle_mass']=="on"?true:false,
            "exercise_freq"=>$request['exercise_freq'],
            "exercise_time"=>$request['exercise_time'],
            "tone"=>$request['tone']=="on"?true:false,
            "lifestyle_health"=>$request['lifestyle_health']=="on"?true:false
        ];

        $clinic_history = [
            "glicemia_detail"=>$request['glicemia_detail'],
            "colesterol_detail"=>$request['colesterol_detail'],
            "trigli_detail"=>$request['trigli_detail'],
            "uric_acid_detail"=>$request['uric_acid_detail'],
            "f_intestinal_detail"=>$request['f_intestinal_detail'],
            "f_alergies_detail"=>$request['f_alergies_detail'],
            "history_personal_detail"=>$request['history_personal_detail'],
            "history_family_detail"=>$request['history_family_detail'],
            "medication_detail"=>$request['medication_detail']
        ];

        $antropometrics = [
            "body_height"=>$request['body_height'],
            "body_weight"=>$request['body_weight'],
            "body_bmi"=>$request['body_bmi'],
            "body_fat_mass"=>$request['body_fat_mass'],
            "body_chest"=>$request['body_chest'],
            "body_waist"=>$request['body_waist'],
            "body_waist_hip"=>$request['body_waist_hip'],
            "body_hip"=>$request['body_hip'],
            "date"=>$request['appDate']
        ];

        $food_diary = [
            "Wakeup_time"=>$request['Wakeup_time'],
            "bed_time"=>$request['bed_time'],
            "breakfast_time"=>$request['breakfast_time'],
            "breakfast_Description"=>$request['breakfast_Description'],
            "breakfast_place"=>$request['breakfast_place'],
            "MorningS1_time"=>$request['MorningS1_time'],
            "MorningS1_Description"=>$request['MorningS1_Description'],
            "MorningS1_place"=>$request['MorningS1_place'],
            "MorningS2_time"=>$request['MorningS2_time'],
            "MorningS2_Description"=>$request['MorningS2_Description'],
            "MorningS2_place"=>$request['MorningS2_place'],
            "lunch_time"=>$request['lunch_time'],
            "lunch_Description"=>$request['lunch_Description'],
            "lunch_place"=>$request['lunch_place'],
            "AfternoonS1_time"=>$request['AfternoonS1_time'],
            "AfternoonS1_Description"=>$request['AfternoonS1_Description'],
            "AfternoonS1_place"=>$request['AfternoonS1_place'],
            "AfternoonS2_time"=>$request['AfternoonS2_time'],
            "AfternoonS2_Description"=>$request['AfternoonS2_Description'],
            "AfternoonS2_place"=>$request['AfternoonS2_place'],
            "dinner_time"=>$request['dinner_time'],
            "dinner_Description"=>$request['dinner_Description'],
            "dinner_place"=>$request['dinner_place'],
            "supper_time"=>$request['supper_time'],
            "supper_Description"=>$request['supper_Description'],
            "supper_place"=>$request['supper_place'],
            "weekend_Description"=>$request['weekend_Description'],
        ];

        $array = ["objectives"=>$objectives,"clinic_history"=>$clinic_history,"antropometrics"=>$antropometrics,"food_diary"=>$food_diary];



        return $array;
    }



    public function add_online_appointment(Request $request){
        $this->validate($request, [
            'appDate' => 'required',
            'Wakeup_time'=>'required',
            'nid'=>'required',
            'uid'=>'required',
            'attachments' => 'required',
        ]);

        $con = array_values($this->SendAjaxJson($request));
        $fileName = time().'.'.$request->attachments->extension();
        $request->attachments->move(storage_path('onlineAppointment'), $fileName);
        $onlineAppointment = OnlineAppointment::create([
            'date'=>$request['appDate'],
            'objectives'=>json_encode($con[0]),
            'clinic_history'=>json_encode($con[1]),
            'antropometrics'=>json_encode($con[2]),
            'observations'=>$request['observations'],
            'food_diary'=>json_encode($con[3]),
            'patientId'=>$request['uid'],
            'attachments'=>$fileName,
            'nutritionistId'=>$request['nid'],
        ]);

        if($onlineAppointment){
            return redirect()->route('online_appointments')->with('success','Appointment Added successfully !');
        }else{
            return redirect()->route('online_appointments')->with('error','Error !');
        }



    }


    public function showSingleOnlineAppointment(Request $request){

        //$onlinAappointment = OnlineAppointment::where('id', $request->input('id'));
        $onlinAappointment = OnlineAppointment::find($request->input('id'));
        $onlinAappointment->appDate = $onlinAappointment->date;
        unset($onlinAappointment->date);

        return $onlinAappointment;
    }

    public function OnlineUpdate(Request $request){
        $this->validate($request, [
            'appDate' => 'required',
            'Wakeup_time'=>'required',
            'attachments'=>'nullable'
        ]);

        $con = array_values($this->SendAjaxJson($request));

        if($request->attachments != "") {
            $fileName = time() . '.' . $request->attachments->extension();
            $request->attachments->move(storage_path('onlineAppointment'), $fileName);
        }else{
            $fileName ='';
        }

        if($fileName != '') {
            $onlineAppointment = OnlineAppointment::where('id', $request->input('appId'))->update([
                'date' => $request['appDate'],
                'objectives' => json_encode($con[0]),
                'clinic_history' => json_encode($con[1]),
                'antropometrics' => json_encode($con[2]),
                'observations' => $request['observations'],
                'food_diary' => json_encode($con[3]),
                'attachments' => $fileName,
            ]);
        }else{
            $onlineAppointment = OnlineAppointment::where('id', $request->input('appId'))->update([
                'date' => $request['appDate'],
                'objectives' => json_encode($con[0]),
                'clinic_history' => json_encode($con[1]),
                'antropometrics' => json_encode($con[2]),
                'observations' => $request['observations'],
                'food_diary' => json_encode($con[3]),
            ]);
        }




            return redirect()->route('online_appointments')->with('success','Appointment updated successfully !');

    }


    public function ajaxOnlineDelete(Request $request){
        $query = OnlineAppointment::destroy( $request->input('id'));
        return $query;
    }

    public function loadPrevious(Request $request){
        $query = OnlineAppointment::find($request->input('id'));
        $query->appDate = $query->date;
        unset($query->date);
        return $query;
    }




}
