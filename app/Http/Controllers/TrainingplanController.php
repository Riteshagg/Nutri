<?php
namespace App\Http\Controllers;

use App\InitialPlan;
use App\Nutritionist;
use App\OnGoingPlan;
use App\Trainer;
use App\User;
use App\Appointment;
use App\OnlineAppointment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function GuzzleHttp\Promise\all;


class TrainingplanController extends Controller
{

    public function initialtraining(Request $request){

        $trainers= $patient_list = $initialTrainingList = array();

        if (Auth::user()->hasRole('Admin')) :
            $trainers = User::role('Personal_trainer')->orderBy('id', 'DESC')->paginate();
        endif;

        if (Auth::user()->hasRole('Admin')) :
            $selectedTrainer = trim($request->input('nid'));
        elseif (Auth::user()->hasRole('Personal_trainer')):
            $selectedTrainer = Auth::id();
        endif;

        $patients = Trainer::with('user')->where('trainerId', $selectedTrainer)->get();

        $uid = $request->input('uid');

        if (isset($uid)) {
            $initialTrainingQuery = InitialPlan::where('patientId', $uid);
            if ($initialTrainingQuery->exists())
                $initialTrainingList = $initialTrainingQuery->get();
            else
                $initialTrainingList = array();
        }

        return view('pages.initialPlan', compact('trainers', 'patients', 'initialTrainingList'));
    }




    public function SendAjaxJson($request)
    {
        $count = count($request['objective']);

        $planData  = [];
        for ($i=0;$i<$count;$i++) {
            $planData[] = [
                "objective" => $request['objective'][$i],
                "exercise" => $request['exercise'][$i],
                "intensity" => $request['intensity'][$i],
                "volume" => $request['volume'][$i],
                "series" => $request['series'][$i],
                "repetitions" => $request['repetitions'][$i],
                "rest_time" => $request['rest_time'][$i],

            ];
        }
        return $planData;

    }



    public function initialUpdate(Request $request){
        $this->validate($request, [
            'objective' => 'required',
            'exercise' => 'required',
            'intensity' => 'required',
            'volume' => 'required',
            'series' => 'required',
            'repetitions' => 'required',
            'rest_time' => 'required',
            'dntropemtric_data' => 'required',
            'goal' => 'required',
            'time_frame' => 'required',
            'motivation' => 'required',
        ]);
        $observations=[
            "dntropemtric_data" => $request['dntropemtric_data'],
            "goal" => $request['goal'],
            "time_frame" => $request['time_frame'],
            "motivation" => $request['motivation'],
        ];
        $planData = [
            "objective" => $request['objective'],
            "exercise" => $request['exercise'],
            "intensity" => $request['intensity'],
            "volume" => $request['volume'],
            "series" => $request['series'],
            "repetitions" => $request['repetitions'],
            "rest_time" => $request['rest_time'],

        ];

        $InitialPlan = InitialPlan::where('id', $request->input('id'))->update([
            'planData'=> json_encode($planData),
            'observations'=>json_encode($observations),
        ]);

        if($InitialPlan){
            return redirect()->route('initial_training_Plan')->with('success','Initial training plan updated successfully !');
        }else{
            return redirect()->route('initial_training_Plan')->with('error','Error !');

        }
    }

    public function deletePlan(Request $request){
        $InitialPlan = InitialPlan::destroy( $request->input('id'));
        return $InitialPlan;
    }




    public function initial_plan_store(Request $request){
        $this->validate($request, [
            'objective' => 'required',
            'exercise' => 'required',
            'intensity' => 'required',
            'volume' => 'required',
            'series' => 'required',
            'repetitions' => 'required',
            'rest_time' => 'required',
            'dntropemtric_data' => 'required',
            'goal' => 'required',
            'time_frame' => 'required',
            'motivation' => 'required',
        ]);

        $observations=[
            "dntropemtric_data" => $request['dntropemtric_data'],
            "goal" => $request['goal'],
            "time_frame" => $request['time_frame'],
            "motivation" => $request['motivation'],
        ];

        $con = $this->SendAjaxJson($request);
        $count = count($con);
        if (Auth::user()->hasRole('Admin')){
            $trainerId = trim($request->input('nid'));
        } else { //set the session by default when its a trainer
            $trainerId = Auth::id();
        }

        for ($i=0;$i<$count;$i++) {
            $initial_plan_detail = [
                'patientId'=>$request->input('uid'),
                'trainerId'=>$trainerId,
                'planData'=> json_encode($con[$i]),
                'observations'=>json_encode($observations),

            ];
            $InitialPlan = InitialPlan::create($initial_plan_detail);
        }

        if($InitialPlan){
            return redirect()->route('initial_training_Plan')->with('success','Initial training plan Added successfully !');
        }else{
            return redirect()->route('initial_training_Plan')->with('error','Error !');

        }
    }




    public function ongoingTraining(Request $request){

        $trainers= $patient_list =$ongoing_training_plan=$queries=  array();

        if (Auth::user()->hasRole('Admin')) :
            $trainers = User::role('Personal_trainer')->orderBy('id', 'DESC')->paginate();
        endif;

        if (Auth::user()->hasRole('Admin')) :
            $selectedTrainer = trim($request->input('nid'));
        elseif (Auth::user()->hasRole('Personal_trainer')):
            $selectedTrainer = Auth::id();
        endif;

        $patients = Trainer::with('user')->where('trainerId', $selectedTrainer)->get();

        $uid = $request->input('uid');

        if (isset($uid)) {
            $onappointment_query = OnGoingPlan::where('patientId', $uid);
            $query = OnGoingPlan::where('patientId', $uid)->orderBy('id', 'DESC');


            if ($onappointment_query->exists()) {
                $ongoing_training_plan = $onappointment_query->get();
            }
            else {
                $ongoing_training_plan = array();
            }

            if( $query != ""){
                $queries = $query->first();
            }else{
                $queries = '';
            }


        }

        return view('pages.onGoingPlan', compact('trainers', 'patients','ongoing_training_plan','queries'));
    }


  public function sendJson($request){
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
      $array = ["objectives"=>$objectives,"antropometrics"=>$antropometrics];
      return $array;
  }


    public function ongoing_plan_store(Request $request){
        $this->validate($request, [
            'appDate' => 'required',
            'nid'=>'required',
            'uid'=>'required',
            'attachment' => 'required',
        ]);

        $trainingPlan = [];
        $count = count($request['Ordem']);

        for ($i=0;$i<$count;$i++){

            $trainingPlan[] = [
                'Ordem'=>$request['Ordem'][$i],
                'Exercicio'=>$request['Exercicio'][$i],
                'intensidade'=>$request['intensidade'][$i],
                'Series'=>$request['Series'][$i],
                'Repeticoes'=>$request['Repeticoes'][$i],
                'Descanso'=>$request['Descanso'][$i]
            ];


            }

        $con = array_values($this->sendJson($request));
        $fileName = time().'.'.$request->attachment->extension();
        $request->attachment->move(storage_path('onGoingTrainingPlan'), $fileName);
        if (Auth::user()->hasRole('Admin')){
            $trainerId = trim($request->input('nid'));
        } else { //set the session by default when its a trainer
            $trainerId = Auth::id();
        }
        $ongoingPlan = OnGoingPlan::create([
                'date'=>$request['appDate'],
                'objectives'=>json_encode($con[0]),
                'anthropometricData'=>json_encode($con[1]),
                'motivation'=>$request['Motivaca'],
                'espacoTemporal'=>$request['espacoTemporal'],
                'patientId'=>$request['uid'],
                'trainingPlan'=>json_encode($trainingPlan),
                'attachment'=>$fileName,
                'alteracaoAoTreino'=>$request['alteracaoAoTreino'],
                'adaptacoes'=>$request['adaptacoes'],
                'trainerId'=>$trainerId,
            ]);


        if($ongoingPlan){
            return redirect()->route('ongoing_training_Plan')->with('success','On Going training plan Added successfully !');
        }else{
            return redirect()->route('ongoing_training_Plan')->with('error','Error !');

        }
    }


    public function onGoingUpdate(Request $request){
        $this->validate($request, [
            'appDate' => 'required',
            'attachment'=>'nullable'
        ]);


        $con = array_values($this->sendJson($request));
        if($request->attachment != "") {
            $fileName = time() . '.' . $request->attachment->extension();
            $request->attachment->move(storage_path('onGoingTrainingPlan'), $fileName);
        }else{
            $fileName ='';
        }

        $trainingPlan = [];
        $count = count($request['Ordem']);

        for ($i=0;$i<$count;$i++){

            $trainingPlan[] = [
                'Ordem'=>$request['Ordem'][$i],
                'Exercicio'=>$request['Exercicio'][$i],
                'intensidade'=>$request['intensidade'][$i],
                'Series'=>$request['Series'][$i],
                'Repeticoes'=>$request['Repeticoes'][$i],
                'Descanso'=>$request['Descanso'][$i]
            ];


        }

        if($fileName != '') {
            $OnGoingPlan = OnGoingPlan::where('id', $request->input('appId'))->update([
                'date'=>$request['appDate'],
                'objectives'=>json_encode($con[0]),
                'anthropometricData'=>json_encode($con[1]),
                'motivation'=>$request['Motivaca'],
                'espacoTemporal'=>$request['espacoTemporal'],
                'attachment'=>$fileName,
                'alteracaoAoTreino'=>$request['alteracaoAoTreino'],
                'trainingPlan'=>json_encode($trainingPlan),
                'adaptacoes'=>$request['adaptacoes'],
            ]);
        }else{
            $OnGoingPlan = OnGoingPlan::where('id', $request->input('appId'))->update([
                'date'=>$request['appDate'],
                'objectives'=>json_encode($con[0]),
                'anthropometricData'=>json_encode($con[1]),
                'motivation'=>$request['Motivaca'],
                'espacoTemporal'=>$request['espacoTemporal'],
                'alteracaoAoTreino'=>$request['alteracaoAoTreino'],
                'trainingPlan'=>json_encode($trainingPlan),
                'adaptacoes'=>$request['adaptacoes'],
            ]);
        }
        return redirect()->route('ongoing_training_Plan')->with('success','Appointment updated successfully !');
    }

    public function deleteOnGoingPlan(Request $request){

        $deleteOnGoingPlan = OnGoingPlan::destroy( $request->input('id'));
        return $deleteOnGoingPlan;
    }

    public function ajaxHandlerOnGoing(Request $request){
        $onGoingTraining = OnGoingPlan::find($request->input('id'));

        return $onGoingTraining;
    }

    public function ajaxHandler(Request $request){
        $iinitialTraining = InitialPlan::find($request->input('id'));

        return $iinitialTraining;
    }

    public function loadPrevious(Request $request){
        $query = OnGoingPlan::find($request->input('id'));
        $query->appDate = $query->date;
        unset($query->date);
        return $query;
    }


}

