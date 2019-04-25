<?php
/**
 * 商品管理控制器
 * User: 路明非
 * Date: 2019/4/9
 * Time: 13:34
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\File;
use think\Request;

class Product extends Controller
{
    //商品管理 - 商品分类
    public function product_category()
    {
        $data = Db::table('product_type')->select();
        $data = $this->createSon($data);
//        var_dump($data);die;
        return $this->fetch('product_category',['data'=>$data]);
    }

    public function add()
    {
        $data = \db('product_type')->select();
        $data = $this->createSon($data);
        return view('add_product_category',['data'=>$data]);
    }

    //商品分类中的添加分类
    public function adds()
    {
        $data = input();
        $type_name = db('product_type')->where('type_name',$data['type_name'])->find();
        if($type_name){
            $this->error('分类名已存在','add');
        }
        $res = Db::table('product_type')->insert($data);
        if($res){
            return $this->success('分类添加成功','product_category');
        }else{
            return $this->error('分类添加失败');
        }
    }
    //商品分类-修改分类
    public function save()
    {
        $id = input('get.id');
        $data = Db::table('product_type')->where('id',$id)->find();
        $type = db('product_type')->select();
        $types = $this->createSon($type);
        return $this->fetch('updproduct_type',['data'=>$data,'type'=>$types]);
    }
    //商品分类-修改分类
    public function updproduct_types()
    {
        $data = input();
        $type_name = db('product_type')->where('type_name',$data['type_name'])->find();
        if($type_name){
            $this->error('分类名已存在','save');
        }
        $datas = Db::table('product_type')->where('id', $data['id'])->update($data);
        if($datas){
            return $this->success('分类修改成功','product_category');
        }else{
            return $this->error('分类修改失败');
        }
    }
    //商品分类-删除
    public function del()
    {
        $id = input('get.id');
        $data =  Db::table('product_type')->where('id',$id)->delete();
        if($data){
            return $this->success('删除成功','product_category',1);
        }else{
            return $this->error('删除失败');
        }
    }
    //商品管理 - 添加商品
    public function addproduct()
    {
        $post = input('post.');
        $files = request()->file('image');
        foreach ($files as $file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            $image[] = $info -> getSaveName();
        }
//        //implode数组转字符串
        $post['image'] = implode(',',$image);
        $data = Db::table('product_add')->insert($post);
                    if($data){
                       return $this->success('商品添加成功','index/index/product');
                   }else{
                       return $this->error('商品添加失败');
                  }
    }
    //商品管理 - 修改商品
    public function updproduct()
    {
        $id = input('get.id');
        $data =  Db::table('product_add')
            ->alias('a')
            ->join('product_type b','a.protype = b.id')
            ->field('a.id,a.proname,a.protype,a.proprice,a.prodescribe,a.image,a.keyword,a.describe,b.type_name')
            ->where(['a.id'=>$id])
            ->find();
        $image = explode(',',$data['image']);
//        foreach ($data as $k =>$v){
//            echo "<pre>";
//            print_r($k);
//            print_r($v);die;
//            $data[$k]['image'] = explode(',',$v['image']);
//        }
        print_r($image);
        $type = Db::table('product_type')->select();
        return $this->fetch('updproduct',['data'=>$data,'type'=>$type,'image'=>$image]);
    }
    //商品管理 - 删除商品
    public function delproduct()
    {
        $id = input('get.id');
        $data = Db::table('product_add')->where('id',$id)->delete();
        if($data){
            echo '1';//1代表删除成功
        }else{
            echo '2';//2代表删除失败
        }
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