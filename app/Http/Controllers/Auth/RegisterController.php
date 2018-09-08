<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Mail;
use Notification;
use App\Mail\verifyUserByEmail;
use App\Notifications\MailNotification;
use App\Notifications\MarkdownNotification;
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
    protected $redirectTo = '/home';

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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verify_email_token'=> str_random(25),
        ]);

        $user->notify(new MailNotification);
        // Notification::send($user,new MarkdownNotification);
        // Mail::to($user->email)->send(new verifyUserByEmail($user));
        return $user;
    }

    public function register(Request $request){
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath())->with('message','Check your email to verify Account');
    }

    protected function registered(Request $request, $user){
        //
    }

    public function verifyUser($email,$token){
        $user  = User::where(['email'=>$email,'verify_email_token'=>$token])->first();
        if($user){
            $user->verify_email_token = null;
            $user->status = 1;
            if($user->save()){
                return redirect('login')->with('message','Your account successfully verify');
            }else{
                return redirect('login')->with('message','Email or Token is invalid');
            }
        }else{
            return redirect('login')->with('message','User not found');
        }
    }
}
