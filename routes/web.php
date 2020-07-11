<?php

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();


Route::group(['middleware' => ['auth']], function() {


    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    Route::get('/download/{id}', 'HomeController@download')->name('download');
    Route::get('/download-online/{id}', 'HomeController@downloadOnline')->name('downloadOnline');
    Route::get('/trainer-download-online/{id}', 'HomeController@trainerDownloadOnline')->name('trainerDownloadOnline');
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::get('/loadPreviousOnlineAppointment', 'AppointmentController@loadPrevious')->name('loadPreviousOnlineAppointment');
    Route::get('/loadPreviousOnGoingPlan', 'TrainingplanController@loadPrevious')->name('loadPreviousOnGoingPlan');
    Route::get('/change-password', 'UserController@change_password')->name('change_password');
    Route::post('/update-password', 'UserController@update_password')->name('update_password');

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::post('/filter-trainer-nutri', 'PatientController@filterTrainerNutri')->name('filter_trainer_nutri');
        Route::get('/nutritionist-list', 'NutritionistController@index')->name('nutritionist_list');
        Route::get('/nutritionist-add', 'NutritionistController@create')->name('nutritionist_add');
        Route::post('/nutritionist-save', 'NutritionistController@save_user')->name('nutritionist_save');
        Route::post('/nutricionistas-update', 'NutritionistController@update')->name('nutricionistas_update');
        Route::get('/ajaxnutritionist', 'NutritionistController@ajaxNut')->name('ajaxnutritionist');
        Route::get('/nutritionist-delete/{id}', 'NutritionistController@delete')->name('nutritionist_delete');
        Route::get('/personal-trainer-add', 'PersonalTrainerController@create')->name('personal_trainer_add');
        Route::post('/personal-trainer-save', 'PersonalTrainerController@save_user')->name('personal_trainer_save');
        Route::get('/personal-trainer-list', 'PersonalTrainerController@index')->name('personal_trainer_list');
        Route::post('/trainer-update', 'PersonalTrainerController@update')->name('trainer_update');
        Route::get('/ajaxPersonalTrainer', 'PersonalTrainerController@ajaxPersonalTrainer')->name('ajaxPersonalTrainer');
        Route::get('/trainer-delete/{id}', 'PersonalTrainerController@delete')->name('trainer_delete');

    });

    Route::group(['middleware' => ['role:Admin|Nutritionist']], function () {


        Route::get('/patients', 'PatientController@index')->name('patientlist');
        Route::get('/patients-add', 'PatientController@create')->name('patientadd');
        Route::post('/patients-store', 'PatientController@store')->name('patientstore');
        Route::post('/patients-update', 'PatientController@update')->name('patient_update');
        Route::get('/patients-delete/{id}', 'PatientController@delete')->name('patient_delete');
        Route::get('/ajaxpatients', 'PatientController@ajaxHandler')->name('ajaxpatient');

        Route::get('/appointments', 'AppointmentController@appointment')->name('appointments');
        Route::post('/appointments-store', 'AppointmentController@appointment_store')->name('appointments_store');

        Route::get('/ajaxappointments', 'AppointmentController@ajaxHandler')->name('ajaxappointments');
        Route::get('/deleteappointment', 'AppointmentController@ajaxDelete')->name('deleteappointment');
        Route::post('/updateappointment', 'AppointmentController@appointUpdate')->name('updateappointment');

        Route::get('/online-appointments', 'AppointmentController@onlineAppointment')->name('online_appointments');
        Route::get('/online-appoint', 'AppointmentController@onlineAppoint')->name('online_appoint');
        Route::post('/add-online-appointment', 'AppointmentController@add_online_appointment')->name('add_online_appointment');
        Route::get('/showSingleOnlineAppointment', 'AppointmentController@showSingleOnlineAppointment')->name('showSingleOnlineAppointment');
        Route::post('/updateOnlineAppointment', 'AppointmentController@OnlineUpdate')->name('updateOnlineAppointment');
        Route::get('/deleteOnlineAppointment', 'AppointmentController@ajaxOnlineDelete')->name('deleteOnlineAppointment');

    });

    Route::group(['middleware' => ['role:Admin|Personal_trainer']], function () {
        Route::get('/trainer-patients-add', 'PatientController@trainerCreate')->name('trainerPatientAdd');
        Route::post('/trainer-patients-store', 'PatientController@trainerPatientsStore')->name('trainer_patient_store');
        Route::get('/trainer-patients-list', 'PatientController@trainerPatientList')->name('trainer_patient_list');
        Route::get('/ajaxTrainerPatients', 'PatientController@ajaxTrainerPatients')->name('ajaxTrainerPatients');
        Route::post('/trainer-patients-update', 'PatientController@trainerPatientsUpdate')->name('trainer_patient_update');
        Route::get('/trainer-patient-delete/{id}', 'PatientController@trainerPatientsDelete')->name('trainer_patient_delete');

        Route::get('/initial-training-Plan', 'TrainingplanController@initialtraining')->name('initial_training_Plan');
        Route::post('/initial-plan-store', 'TrainingplanController@initial_plan_store')->name('initial_plan_store');

        Route::get('/ongoing-training-Plan', 'TrainingplanController@ongoingTraining')->name('ongoing_training_Plan');
        Route::post('/ongoing-plan-store', 'TrainingplanController@ongoing_plan_store')->name('ongoing_plan_store');

        Route::get('/ajaxhandler', 'TrainingplanController@ajaxHandler')->name('ajaxappointments');
        Route::post('/updatinitialplan', 'TrainingplanController@initialUpdate')->name('update_initial_plan');
        Route::get('/deleteplan', 'TrainingplanController@deletePlan')->name('delete_plan');

        Route::get('/ajaxhandlerongoing', 'TrainingplanController@ajaxHandlerOnGoing')->name('ajax_handler_on_going');
        Route::get('/deleteOnGoingPlan', 'TrainingplanController@deleteOnGoingPlan')->name('delete_ongoing_plan');
        Route::post('/updateEditOnGoingPlan', 'TrainingplanController@onGoingUpdate')->name('updateOnlineAppointment');

    });



});




