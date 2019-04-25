<?php
/**
 * 后台登录
 * User: 路明非
 * Date: 2019/4/8
 * Time: 13:44
 */
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        return view('index');
    }
    //处理登录
    public function login()
    {
        $admin_name = input('post.admin_name');
        $admin_pwd  = md5(input('post.admin_pwd'));

        $data = Db::table('admin')
        ->where('admin_name',$admin_name)
        ->where('admin_pwd',$admin_pwd)
        ->find();
        if($data){
            session('admin',$data);
            return $this->success('登录成功','http://127.0.0.1/dzsc/public/index.php/index');
        }else{
            return $this->error('用户名密码错误');
        }
    }


}