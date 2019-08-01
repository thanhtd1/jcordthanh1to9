<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Admin;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Mail;
use Faker\Factory;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

	public function login(Request $request)
    { 
        $rules = [
    		'username' =>'required|string',
    		'password' => 'required|min:5'
    	];
    	$messages = [
    		'username.required' => 'Tên đăng nhập là trường bắt buộc',
    		'username.string' => 'Tên đăng nhập không đúng định dạng',
    		'password.required' => 'Mật khẩu là trường bắt buộc',
    		'password.min' => 'Mật khẩu phải chứa ít nhất 5 ký tự',
    	];
    	$validator = Validator::make($request->all(), $rules, $messages);

    	if ($validator->fails()) {
			return "<script>console.log(".(json_encode($validator->errors())).");</script>";
    		return response(['error'=>$validator->errors()]);
			// dd( $request->all());
		} 
		else {
			// dd( $request->all());
    		$username = $request->input('username');
			$password = $request->input('password'); 
			$a=Auth::guard('client');
    		if( Auth::guard('client')->attempt(['username' => $username,'password' =>$password])) {
				if(Auth::guard('admins')->check()){
					Auth::guard('admins')->logout();
				}
				$success = new MessageBag(['successlogin' => 'Đăng nhập thành công']);
    			return redirect('/')->withErrors($success);
    		}
    		elseif(Auth::guard('admins')->attempt(['username' => $username,'password' =>$password])) {
				if(Auth::guard('client')->check()){
					Auth::logout();
				}
				$success = new MessageBag(['successlogin' => 'Đăng nhập thành công']);
    			return redirect('/admin')->withErrors($success);
			} 
			else {
    			$errors = new MessageBag(['errorlogin' => 'Tên đăng nhập hoặc mật khẩu không đúng']);
    			return redirect('/')->withInput()->withErrors($errors);
    		}
    	}
	}

	public function forgot(Request $request)
	{
		if($request->has('tennd') && !$request->has('email')){
			$ten_nd=User::where('mssv',$request->input('tennd'))->first();
			if(!$ten_nd){
				$ten_nd=Admin::where('manv',$request->input('tennd'))->first();
			}
			if($ten_nd){
				return view('auth.passwords.forgot',['user'=>$ten_nd]);
			}
			else {
				return view('auth.passwords.forgot',['user'=>[]]);
			}
		}
		elseif ($request->has('tennd') && $request->has('email')) {
			$email_nd=User::where([['mssv',$request->input('tennd')],['email',$request->input('email')]])->first();
			if(!$email_nd){
				$email_nd=Admin::where([['manv',$request->input('tennd')],['email',$request->input('email')]])->first();
			}
			if(!$email_nd){
				$ten_nd=User::where('mssv',$request->input('tennd'))->first();
				if(!$ten_nd){
					$ten_nd=Admin::where('manv',$request->input('tennd'))->first();
				}
				if($ten_nd){
					$error = new MessageBag(['email' => 'Email bạn nhập không đúng']);
					return view('auth.passwords.forgot',['user'=>$ten_nd])->withErrors($error);
				}
			}
			else{
				$faker=Factory::create();
				$email_nd->remember_token=$faker->sha1;
				$email_nd->save();
				Mail::send(
					'auth.change_mail_template', //mail template(change_mail_template.blade.php)
					['remember_token'=>$email_nd->remember_token,'host'=>self::HOST,'action'=>'forgot','username'=>$email_nd->mssv ?: $email_nd->manv],//mảng chứa dữ liệu trả về cho template
					function($message) use ($email_nd) {
						$message->to($email_nd->email,'Student STU')
							->subject('Xác thực mail STU');
					}
				);
				return view('auth.wait_change_mail',['email'=>$email_nd->email]);
			}
		}
		return view('auth.passwords.forgot');
	}

	public function post_change_mail(Request $request){
		$rules = [
    		'password' =>['required_without:email',function ($attribute, $value, $fail) use ($request) {
				$user = Auth::guard('admins')->user() ?: Auth::user();
				$pass = $user->password;
				if($user && !Hash::check($request->input('password'), $pass)){
                    return $fail("Mật khẩu của bạn không đúng, vui lòng liên hệ PDT");
				}
			},'min:8'],
			'email'=>'email'
    	];
    	$messages = [
			'password.required_without' => 'Bạn chưa điền mật khẩu',
			'password.min' => 'Mật khẩu phải nhiều hơn 8 ký tự',
			'email.email'=>'Email bạn nhập không đúng định dạng'
    	];
    	$validator = Validator::make($request->all(), $rules, $messages);

    	if ($validator->fails()) {
    		return redirect()->back()->withErrors($validator)->withInput($request->all());
		}
		else{
			$user= Auth::guard('admins')->user() ?: Auth::user();
			if($request->has('email'))
			{
				$old_email=$user->email;
				$email=$request->input('email');
				if($old_email!=$email){
					$user->email=$email;
					$user->save();
					Mail::send(
						'auth.change_mail_template', //mail template(change_mail_template.blade.php)
						['host'=>self::HOST,'new_email'=>$email],//mảng chứa dữ liệu trả về cho template
						function($message) use ($old_email) {
							$message->to($old_email,'Student STU')
								->subject('Thông báo thay đổi địa chỉ Email');
						}
					);
				}
				$success = new MessageBag(['successlogin' => 'Đổi email thành công']);
				return redirect('/')->withErrors($success);
			}
			else {
				return view('auth.change_mail',['confirm'=>true]);
			}
			return view('auth.wait_change_mail',['email'=>$request->input('email')]);
		} 
	}

	public function reset_password(Request $request,$user_reset=null){
		if($request->has("token"))
		{
			$rules = [
				'password' => 'required|min:8',
				're_password' => 'required|same:password'
			];
			$messages = [
				'password.required' => 'Bạn chưa điền mật khẩu mới',
				'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự',
				're_password.required' => 'Bạn chưa điền chưa nhập lại mật khẩu',
				're_password.same'=>"Mật khẩu không khớp"
			];
		}
		else{
			$rules = [
				'old_password' => ['required',function ($attribute, $value, $fail) use ($request) {
					$user = Auth::guard('admins')->user() ?: Auth::user();
					$pass = $user->password;
					if($user && !Hash::check($request->input('old_password'), $pass)){
						return $fail("Mật khẩu cũ của bạn không đúng, vui lòng liên hệ PDT");
					}
				}],
				'password' => 'required|min:8',
				're_password' => 'required|same:password'
			];
			$messages = [
				'old_password.required' => 'Bạn chưa điền mật khẩu cũ',
				'password.required' => 'Bạn chưa điền mật khẩu mới',
				'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự',
				're_password.required' => 'Bạn chưa điền chưa nhập lại mật khẩu',
				're_password.same'=>"Mật khẩu không khớp"
			];
		}
		
    	$validator = Validator::make($request->all(), $rules, $messages);

    	if ($validator->fails()) {
    		return redirect()->back()->withErrors($validator)->withInput();
		} else {
			if($request->has('token'))
			{
				$user=User::where('mssv',$user_reset)->first();
				if(!$user){
					$user=Admin::where('manv',$user_reset)->first();
				}
				$user->password=Hash::make($request->input('password'));
				$user->save();
				$error = new MessageBag(['reset_success' => 'Đổi mật khẩu thành công']);
				return redirect()->route('reset_password_forgot',['username'=>$user_reset])->withErrors($error);
			}
			else {
				$user= Auth::guard('admins')->user() ?: Auth::user();
				$user->password=Hash::make($request->input('password'));
				$user->save();
				$this->logout();
				$error = new MessageBag(['reset_success' => 'Đổi mật khẩu thành công']);
				return view('auth.reset_password',['success'=>true,'action'=>'reset'])->withErrors($error);
			}
		}
	}

	public function logout(){
		if(Auth::guard('admins')->check()){
			Auth::guard('admins')->logout();
		}
		if(Auth::guard('client')->check()){
			Auth::logout();
		}
		$success = new MessageBag(['successlogin' => 'Đăng xuất thành công']);
		return redirect('/')->withErrors($success);
	}
}
