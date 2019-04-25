<?php
namespace app\index\controller;
use think\Request;
use think\Controller;

class Payment extends Controller{
    public function payment_show()
    {
        $data = db('payment')->select();
        return view('payment_method',['data'=>$data]);
    }
}