<?php 
namespace app\home\Controller;

use think\Controller;
use think\Request;
use think\Db;

class Hindex extends Controller
{
	public function index()
	{
        $type = db('product_type')->select();
        $type_data=$this->createSon($type);
        $res = Db::table('product_add')->select();
//        var_dump($type_data);die;
		return view('index',['type_data'=>$type_data,'res'=>$res]);
	}

    public function goods()
    {
        echo "fdhs ";die;
        $attr_id =Request::instance()->param('attr_id');
        // var_dump($attr_id);die;
        $res = Db::query("select * from attribute_value where attr_id = ".$attr_id);
        echo json_encode($res);
    }

    //添加到购物车
    public function Addcar()
    {
        $user = session('userinfo');
        $data = input('get.');
        $data['u_id'] = $user['id'];
        $data1 = DB::table('car')->where('u_id',$user['id'])->where('g_id',$data['g_id'])->find();

        if ($data1) {
            $pronum = $data['pronum']+$data1['pronum'];
            $res = DB::table('car')->where('u_id',$user['id'])->where('g_id',$data['g_id'])->update(['pronum'=>$pronum]);
        }else{
            if(!$user){
                cookie('car_data',$data);
                $this->success('添加购物车成功','page');
            }
            $res = DB::table('car')->insert($data);
        }
        if ($res) {
            echo "1";
        }
    }

	public function Mycar()
	{
	    $user = session('userinfo');
	    if(!$user){
	        return view('hlogin/login');
        }
        $data = DB::table('car')->select();
		return view('my-car',['data'=>$data]);
	}

    //商品数量  减
    public function minus()
    {
        $data = input('get.');
        $pronum = $data['count']-1;
        $prosum = $pronum * $data['proprice'];
        $upd = DB::table('car')->where('g_id',$data['g_id'])->update(['pronum'=>$pronum,'prosum'=>$prosum]);
    }

    //商品数量  加
    public function plus()
    {
        $data = input('get.');
        $pronum = $data['count']+1;
        $prosum = $pronum * $data['proprice'];
        $upd = DB::table('car')->where('g_id',$data['g_id'])->update(['pronum'=>$pronum,'prosum'=>$prosum]);
    }

    public function page()
	{
        $id = Request::instance()->param('id');
        // print_r($id);
        $res = Db::table('product_add')->where('id',$id)->find();
        // var_dump($id);die;
        $res1 = Db::query("select * from attribute");
        $this->assign('res1',$res1);
        $this->assign('res',$res);
        return view('page');
	}

	//付款
	public function Myadd()
	{
		return view('my-add');
	}

	//下单
	public function Myapy()
	{
		return view('my-apy');
	}

    public function member()
    {
        return view('member');
	}

    public function createSon($cat,$parent_id=0){
        $newArr=array();
        foreach($cat as $k=>$v){
            if($v['parent_id']==$parent_id){
                $newArr[$k]=$v;
                $newArr[$k]['son']=$this->createSon($cat,$v['id']);
            }
        }
        return $newArr;
    }

}





 ?>