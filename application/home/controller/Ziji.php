<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use QL\QueryList;
use think\Db;
use think\Request;
use think\Cookie;
use think\Session;
use think\Paginator;
use think\Exception;
use think\Loader;
use think\PHPExcel;

class Ziji extends Controller
{
	//首页
	public function Sanji()
	{
		$data=Db::table('region')->where('parent_id',1)->select();
		//print_r($data);die;
		return $this->fetch("sanji",['data'=>$data]);
	}

	public function selectshi()
	{
		$sheng_id = input('post.sheng_id');

		//echo json_encode($sheng_id);die;	
		$data=Db::table('region')->where('parent_id',$sheng_id)->select();
		echo json_encode($data);
	}

	public function selectxian()
	{
		$shi_id = input('post.shi_id');
		$data=Db::table('region')->where('parent_id',$shi_id)->select();

		echo json_encode($data);
	}


	public function duotu()
	{
		return $this->fetch("duotu");
	}

	public function addtu()
	{
		$files = request()->file('image');
		$str = '';
		foreach($files as $file){
	        // 移动到框架应用根目录/public/uploads/ 目录下
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	        	$time = date("Ymd",time());
	            $str=$time."/".$info->getFilename();
	            $data = ['v_id' => '1', 'img' => $str];
				Db::table('img')->insert($data);
	        }else{
	            // 上传失败获取错误信息
	            echo $file->getError();
	        }    
	    }

	    //至此，只需将当天的时间和图片的保存目录以及对应的商品id存入表中，即可
	}

	public function showtu()
	{
		$data = Db::table('img')->where('v_id',1)->select();
		return $this->fetch("showtu",['data'=>$data]);

	}

	public function fenye()
	{
		$request = Request::instance();
		$page = $request->get('page')?$request->get('page'):'1';
		//print_r($page);die;
		$size = 3;//偏移量 
		$kai = ($page-1)*3;
		//print_r($kai);die;
		$sql = "SELECT * FROM city LIMIT $kai,$size";
		$data = db('city')->query($sql);	
		//print_r($data);die;
		$count = Db::table('city')->count();
		$date['last'] = ceil($count/$size);
		//print_r($date['last']);die;
		$date['on'] = $page-1<1 ? 1 : $page-1;
		$date['next'] = $page+1>$date['last'] ? $page : $page+1;
		//print_r($date['next']);die;
		return $this->fetch("fenye",['data'=>$data,'date'=>$date]);
	}

	public function dengshou()
	{
		return $this->fetch("dengshou");
	}

	public function disanfang()
	{
		$request = Request::instance();
		$data = $request->get();
		//print_r($data['code']);die;
		$url = "https://api.weibo.com/oauth2/access_token?client_id=1010474337&client_secret=ada251bb43808cb41188f7b52c5a5df7&grant_type=authorization_code&redirect_uri=http://localhost/tp/tp5/public/index.php/index/ziji/disanfang&code=".$data['code'];
		$data2 = $this->doCurlPostRequest($url,"132");
		$data2 = json_decode($data2,true);
		$url2 = "https://api.weibo.com/2/users/show.json?access_token={$data2['access_token']}&uid={$data2['uid']}";
	    $info = $this->doCurlPostRequest($url2,'123');
	    if($info){
	      echo "<p>登录成功</p>";
    	}
	}

		 //curl
	public function doCurlPostRequest($url,$requestString,$timeout = 5){
	 if($url == '' || $requestString == '' || $timeout <=0){
	 return false;
	 }
	 $con = curl_init((string)$url);
	 curl_setopt($con, CURLOPT_HEADER, false);
	 curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
	 curl_setopt($con, CURLOPT_POST,true);
	 curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
	 curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
	 curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
	 curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
	 return curl_exec($con); 
	}

}
?>