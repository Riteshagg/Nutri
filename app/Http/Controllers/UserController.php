<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{



    public function change_password(){


        return view('users.change_password');
    }

    public function admin_credential_rules(array $data)
    {
        $messages = [
            'oldPassword.required' => 'Please enter old password',
            'newPassword.required' => 'Please enter new password',
            'confirmpassword.required' => 'Please retype new password',
            'same'    => 'The new password and retype password must match',
        ];

        $validator = Validator::make($data, [
            'oldPassword' => 'required',
            'newPassword' => 'required|same:newPassword',
            'confirmpassword' => 'required|same:newPassword',
        ], $messages);

        return $validator;
    }

    public function update_password(Request $request)
    {

        if(Auth::Check())
        {
            $request_data = $request->All();
            $validator = $this->admin_credential_rules($request_data);
            if($validator->fails())
            {
                //return response()->json(array('error' => $validator->getMessageBag()->toArray()), 400);
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $current_password = Auth::User()->password;
                if(Hash::check($request_data['oldPassword'], $current_password))
                {
                    $user_id = Auth::User()->id;
                    $obj_user = User::find($user_id);
                    $obj_user->password = Hash::make($request_data['newPassword']);;
                    $obj_user->save();

                    return redirect()->route('change_password')->with("success","Password changed successfully !");
                }
                else
                {
                    //$error = array('oldPassword' => 'Please enter correct current password');
                    //return redirect()->back()->withErrors($validator)->withInput();

                    return redirect()->back()->with("error", "Please enter correct current password !");
                }
            }
        }
        else
        {
            return redirect()->route('change_password');
        }
    }


}