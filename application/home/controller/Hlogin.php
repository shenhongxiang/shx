<?php 
namespace app\home\Controller;

use think\Controller;
use think\Request;

	class Hlogin extends Controller
	{
		//登录
		public function login()
		{
			return view('login');
		}

        public function login_do()
        {
            $data = input();
//            $sql = "select * from user where username='$data[name]' or mobile='$data[name]' or email='$data[name]'";
            $user = db('user')->where('username','=',"$data[username]")->whereOr('mobile','=',"$data[username]")->whereOr('email','=',"$data[username]")->find();
            if(!$user){
                return ['status'=>'error','message'=>'用户不存在'];
//                $this->error('用户不存在');
            }
            if(md5($data['password'])!=$user['password']){
                return ['status'=>'error','message'=>'密码错误'];
//                $this->error('密码错误');
            }
            $car_data = cookie('car_data');
            var_dump($car_data);die;
            session('userinfo',$user);
            return ['status'=>'success'];
//            return view('hindex/index',['userinfo'=>session('userinfo')]);
		}

		//注册
		public function register()
		{
			return view('register');
		}

        public function register_do()
        {
            $data = input();
            $username = db('user')->where('username',$data['username'])->value('username');
            $mobile = db('user')->where('mobile',$data['mobile'])->value('mobile');
            $email = db('user')->where('email',$data['email'])->value('email');
            if($username){
                return ['msg'=>'用户名已存在'];
            }
            if($mobile){
                return ['msg'=>'手机号已存在'];
            }
            if($email){
                return ['msg'=>'邮箱已存在'];
            }
            $data['time'] = time();
            $res = db('user')->insert($data);
            if($res){
                return ['msg'=>'注册成功'];
            }
		}

	}
 ?>