<?php
namespace app\index\controller;

use think\Request;
use think\Controller;

class Express extends Controller{
    //展示
    public function show()
    {
        $data = db('freight_company')->select();
        $num = count($data);
        return view('express',['data'=>$data,'num'=>$num]);
    }
    //删除
    public function del()
    {
        $id = input('post.id');
//        var_dump($id);die;
        $res = db('freight_company')->where('id',$id)->delete();
        if($res){
            return ['code'=>1];
        }
    }
    //添加
    public function add()
    {
        if(Request::instance()->isPost()){
            $data = input();
            $res = db('freight_company')->insert($data);
            if($res){
                $this->success('添加成功','show');
            }
        }else{
            return view();
        }
    }
    //修改
    public function update()
    {
        $id = input('get.id');
        $data = db('freight_company')->where('id',$id)->find();
        return view('update',['data'=>$data]);
    }
    public function updates()
    {
        $data = input();
        $res = db('freight_company')->where('id',$data['id'])->update($data);
        if($res){
            $this->success('修改成功','show');
        }
    }
}