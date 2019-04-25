<?php 
namespace app\index\Controller;

use think\Controller;
use think\Db;

class Brand extends Controller
{
	public function add_brand()
	{
		return view('Add_Brand');
	}

	public function add_brandPro()
	{
		$data = input('post.');
		$file = request()->file('b_logo');
		if ($file) {
			$info = $file->move(ROOT_PATH.'public'.DS.'uploads');
			if($info){
				$b_logo = $info->getSaveName();
				$data['b_logo'] = $b_logo;
			}else{
				echo $file->getError();
			}
		}
		$data['b_time'] = date('Y-m-d h:i:s',time());
		$res = Db::table('brand')->insert($data);
		if ($res) {
			$this->success('添加成功！','index/Brand_Manage');
		}
	}

	public function del_brandPro()
	{
		$id = input('get.id');
		$res = Db::table('brand')->delete($id);
		if ($res) {
			echo "1";
		}
	}

	public function upd_brand()
	{
		$id = input('get.id');
        $data = Db::table('brand')->find($id);
        return $this->fetch('upd_brand',['data'=>$data]);
	}

	public function upd_brandPro()
	{
		$data = input('post.');
		$file = request()->file('b_logo');
		if ($file) {
			$info = $file->move(ROOT_PATH.'public'.DS.'uploads');
			if($info){
				$b_logo = $info->getSaveName();
				$data['b_logo'] = $b_logo;
			}else{
				echo $file->getError();
			}
		}
		$data['b_time'] = date('Y-m-d h:i:s',time());
		$res = Db::table('brand')->insert($data);
		if ($res) {
			$this->success('修改成功！','index/Brand_Manage');
		}
	}

	public function del_brandAll()
	{
		$id = input('get.id');
		$id = explode(',', $id);
		$res = Db::table('brand')->delete($id);
		if($res)
        {
            return 1;
        }
	}


}





 ?>