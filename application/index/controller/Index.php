<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
class Index extends Controller
{
    //渲染主页面
    public function index()
    {
        return view();
    }
    //系统首页
    public function home()
    {
        return $this->fetch('home');
    }
    //产品管理 - 产品类表
    public function Products_List()
    {
        return $this->fetch('Products_List');
    }
    //产品管理 - 品牌管理
    public function Brand_Manage()
    {
        $data = Db::table('brand')->where("b_state",1)->select();
        return $this->fetch('Brand_Manage',['data'=>$data]);
    }
//    产品管理 - 分类管理
    public function Category()
    {
        return view('Category_Manage');
    }
    //产品管理 - 分类管理 添加分类
    public function product_category_add()
    {
        return $this->fetch('product-category-add');
    }
    //图片管理 - 广告管理
    public function advertising()
    {
        return $this->fetch('advertising');
    }
    //图片管理 - 分类管理
    public function Sort_ads()
    {
        return $this->fetch('Sort_ads');
    }
    //交易管理 -交易信息
    public function transaction()
    {
        return $this->fetch('transaction');
    }
    //交易管理 - 交易订单图    地图显示不出来 空白
    public function Order_Chart()
    {
        return $this->fetch('Order_Chart');
    }
    //交易管理 - 订单管理
    public function orderform()
    {
        return $this->fetch('orderform');
    }
    //订单管理 - 交易金额
    public function Amounts()
    {
        return $this->fetch('Amounts');
    }
    //订单管理 - 订单处理
    public function Order_handling()
    {
        return $this->fetch('Order_handling');
    }
    //订单管理 - 退款
    public function Refund()
    {
        return $this->fetch('Refund');
    }
    //支付管理 -账户管理
    public function Cover_management()
    {
        return $this->fetch('Cover_management');
    }
    //支付管理 - 支付方式
    public function payment_method()
    {
        return $this->fetch('payment_method');
    }
    //支付管理  - 支付配置
    public function payment_configure()
    {
        return $this->fetch('payment_configure');
    }
    //会员管理 - 会员列表
    public function user_list()
    {
        return $this->fetch('user_list');
    }
    //会员管理 - 等级管理
    public function member_grading()
    {
        return $this->fetch('member-grading');
    }
    //会员管理 - 会员管理记录
    public function integration()
    {
        return $this->fetch('integration');
    }
    //店铺管理 - 店铺列表
    public function Shop_list()
    {
        return $this->fetch('Shop_list');
    }
    //店铺管理 - 店铺审核
    public function shops_audit()
    {
        return $this->fetch('shops_audit');
    }
    //消息管理 - 留言页表
    public function guestbook()
    {
        return $this->fetch('guestbook');
    }
    //消息管理 - 意见反馈
    public function feedback()
    {
        return $this->fetch('feedback');
    }
    //文章管理 - 文章列表
    public function article_list()
    {
        return $this->fetch('article_list');
    }
    //文章管理 - 分类管理
    public function  article_sort()
    {
        return $this->fetch('article_sort');
    }
    //系统管理 -系统设置
    public function systems()
    {
        return $this->fetch('systems');
    }
    //系统管理 - 系统栏目管理
    public function system_section()
    {
        return $this->fetch('system_section');
    }
    //系统管理 - 日志
    public function system_logs()
    {
        return $this->fetch('system_logs');
    }
    //管理员 - 权限管理
    public function admin_competence()
    {
        return $this->fetch('admin_competence');
    }
    //管理员 - 列表
    public function administrator()
    {
        return $this->fetch('administrator');
    }
    //管理员 - 个人信息
    public function admin_info()
    {
        $admin = session('admin');
//        var_dump($admin);die;
        return view('admin_info',['admin'=>$admin]);
    }

    //商品管理 - 商品分类
    public function product_category()
    {
        return $this->fetch('product_category');
    }
    //商品管理 - 商品列表
    public function product()
    {
        $post = input('get.keyword',0);
        $pager = input('pager',1);
        if($post){
            $size = 3;
            $count = db('product_add')->where('proname','like','%'.$post.'%')->count();
            $offset = ($pager-1)*$size;
            $page['total'] = ceil($count/$size);
            $page['last']  = $pager-1<1 ? 1 : $pager-1;
            $page['next']  = $pager+1>$page['total'] ? $page['total'] : $pager+1;
            $data =  Db::table('product_add')
                ->alias('a')
                ->join('product_type b','a.protype = b.id')
                ->where('a.proname','like','%'.$post.'%')
                ->field('a.id,a.proname,a.protype,a.proprice,a.prodescribe,a.image,a.keyword,a.describe,b.type_name')
                ->limit($offset,$size)
                ->select();
        }else{
//            每页的数量
            $size = 3;
            //总数据
            $count = db('product_add')->count();
            //偏移量 是变化的和当前所在页和每页显示条数有关
            $offset = ($pager-1)*$size;
            //总页数
            $page['total'] = ceil($count/$size);
            //上一页
            $page['last']  = $pager-1<1 ? 1 : $pager-1;
            //下一页
            $page['next']  = $pager+1>$page['total'] ? $page['total'] : $pager+1;
            $data =  Db::table('product_add')
                ->alias('a')
                ->join('product_type b','a.protype = b.id')
                ->field('a.id,a.proname,a.protype,a.proprice,a.prodescribe,a.image,a.keyword,a.describe,b.type_name')
                ->limit($offset,$size)
                ->select();
        }
        if(Request::instance()->isAjax()){
            return ['data'=>$data,'page'=>$page,'count'=>$count,'pager'=>$pager,'offset'=>$offset];
        }else{
            return $this->fetch('product',['data'=>$data,'page'=>$page,'count'=>$count,'offset'=>$offset,'pager'=>$pager]);
        }


    }
    //商品管理 - 添加分类
    public function add_product_category()
    {
        $data = \db('product_type')->select();
        $data = $this->createSon($data);
        return view('add_product_category',['data'=>$data]);
    }
    //商品管理 - 添加商品
    public function addproduct()
    {
        $data = Db::table('product_type')->select();
        return $this->fetch('addproduct',['data'=>$data]);
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
