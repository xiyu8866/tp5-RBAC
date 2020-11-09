<?php
namespace app\admin\controller;

use app\admin\model\RoleModel;
use app\admin\model\RuleModel;

class Role extends Base
{
    public function index()
    {
        $db=new RoleModel();
        $info=$db->paginate(8, false, [ 'type'=> 'page\Page','var_page'=>'p']);
        return view('adminRole', ['info'=>$info]);
    }

    public function add()
    {
        $db=new RuleModel();
        $info=$db->where('pid', 0)->select()->toArray();
        $map=[];
        $info=$this->getTree($info, $map);
        return view('roleAdd', ['info'=>$info]);
    }

    public function save()
    {
        $data = request()->post();
        $db=new RoleModel();
        $res=$db->_update($data);
        if ($res) {
            return json(['code'=>1,'添加成功']);
        } else {
            return json(['code'=>0,'添加失败']);
        }
    }

    public function edit()
    {
        $id = request()->get('id');
        $db=new RoleModel();
        $info=$db->where('RoleId', $id)->find();
        $list=(new RuleModel())->where('pid', 0)->select()->toArray();
        $list=$this->getTree($list, $map=[]);
        return view('RoleEdit', ['info'=>$info,'list'=>$list]);
    }

    public function delete()
    {
        $id = request()->get('id');
        $db=new RoleModel();
        $res=$db->where('RoleId', $id)->delete();
        if ($res) {
            return json(['code'=>1,'删除成功']);
        } else {
            return json(['code'=>0,'删除失败']);
        }
    }
}
