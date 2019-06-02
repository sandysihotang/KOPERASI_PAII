<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function ubah(Request $request,$id){
        $data=User::find($id);
        $data->password=bcrypt($request->password);
        $data->save();

    }

    public function forgotPassword(Request $request){
        $data=User::where('email','=',$request->email)->first();
        if($data==null) {
            $data = array();
            $data['message']="User not found";
            return response(json_encode($data),201)
                ->header('Content-Type', 'text/plain');
        }
        Mail::to($data->email)->send(new ForgotPasswordMail("Administrator SKU PIDEL",$data->id));
    }

    public function userRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;
        return response()->json(['success' => 'success'], 200);
    }

    public function check(Request $request)
    {
        return User::where('email', '=', $request->email)
            ->where('status', '=', 0)->get();
    }

    public function getUserKonfimation()
    {
        return User::where('status', '=', 0)->get();
    }

    public function konfirmasi($id)
    {
        User::where('id', '=', $id)->update(['status' => 1]);
        $data = User::find($id);
        MailController::send($data->email);
    }

    public function updateProfile(Request $request)
    {
        $explode = explode(',', $request->avatar);
        $decode = base64_decode($explode[1]);
        if (str_contains($explode[1], 'jpeg'))
            $extension = 'jpg';
        else
            $extension = 'png';

        $filename = str_random() . '.' . $extension;
        $path = public_path() . '/storage/Image/' . $filename;
        file_put_contents($path, $decode);
        $data = User::find($request->id);
        $data->name = $request->nama;
        $data->email = $request->email;
        $data->image = './storage/Image/' . $filename;
        $data->save();
    }
}
