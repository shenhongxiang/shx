<?php
namespace app\index\controller;

use think\Request;
use think\Controller;

class User extends Controller{
    public function user_list()
    {
        $words = input('words','');
        $p = input('p',1);

        $size = 10;
        $dex = ($p-1)*$size;

        $count = db('user')->count();

        $sql = "select * from user where is_del=0 and (username like '%$words%' or mobile like '%$words%' or email like '%$words%') limit $dex,$size";
        $user = db()->query($sql);

        $page['total'] = ceil($count/$size);
        $page['last'] = $p-1<1 ? 1 : $p-1;
        $page['next'] = $p+1>$page['total'] ? $page['total'] : $p+1;

        if(Request::instance()->isAjax()){
            return ['user'=>$user,'count'=>$count,'page'>$page];
        }else{
            return view('user_list',['user'=>$user,'count'=>$count,'page'=>$page]);
        }

//        if($user){
//            return view('user_list',['user'=>$user,'user_num'=>$user_num]);
//        }else{
//            echo '没有数据';
//        }
    }
    public function save()
    {
        $id = input('post.id');
        $sql = "update user set status=(status+1)%2 where id = $id";
        $res = db('user')->execute($sql);
        if($res){
            return ['code'=>1];
        }
    }

    public function del()
    {
        $del_id = input('post.id');
//        db('member')->where('user_id',$del_id)->delete();
        $res = db('user')->where('id',$del_id)->update(['is_del'=>1]);
        if($res){
            return ['code'=>1];
        }
    }
}