<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;


class Sku extends Controller
{
	
	public function index()
	{
		$res = Db::query("select * from product_add");
		$res1 = Db::query("select * from attribute");

		$this->assign('res',$res);
		$this->assign('res1',$res1);

		return view();
	}
	public function goods()
	{
	
		
		$attr_id =Request::instance()->param('attr_id');
		// var_dump($attr_id);die;
		$res = Db::query("select * from attribute_value where attr_id = ".$attr_id);
		echo json_encode($res);
	}
	//sku属性
	public function attr()
	{
		$res = Db::query("select * from product_type inner join product_add on product_type.id = product_add.protype");
		$attrs = Db::query("select * from attribute inner join attribute_value on attribute.attr_id = attribute_value.attr_id");
		$attr =  Db::query("select * from attribute");
		$res1 =  Db::query("select * from product_type");
		$attr_val =  Db::query("select * from attribute_value");

		$this->assign('res',$res);
		$this->assign('res1',$res1);
		$this->assign('attr',$attr);
		$this->assign('attr_val',$attr_val);


		return view('sku');
	}
	//添加sku
	public function add()
	{

		$data = input();
		// var_dump($data);die;
		$data = Db::table('product_add')->insert($data);
		if ($data) 
		{
			$this->success('添加成功');
		}
	}
}