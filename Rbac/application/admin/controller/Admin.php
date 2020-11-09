<?php
namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\admin\model\RoleModel;

class Admin extends Base
{
    public function index()
    {
        $db=new AdminModel();
        $info=$db->paginate(8, false, [ 'type'=> 'page\Page','var_page'=>'p']);
        foreach ($info as $k=>$v) {
            $v['roleId']=(new RoleModel())->where('RoleId', $v['roleId'])->value('name');
        }
        return view('Admin/adminList', ['info'=>$info]);
    }

    public function add()
    {
        $db=new RoleModel();
        $info=$db->select();
        return view('adminAdd', ['role'=>$info]);
    }

    public function save()
    {
        $data = request()->post();
        $db=new AdminModel();
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
        $db=new AdminModel();
        $role=(new RoleModel())->select();
        $info=$db->where('id', $id)->find();
        return view('adminEdit', ['info'=>$info,'role'=>$role]);
    }

    public function delete()
    {
        $id = request()->get('id');
        $db=new AdminModel();
        $res=$db->where('id', $id)->delete();
        if ($res) {
            return json(['code'=>1,'删除成功']);
        } else {
            return json(['code'=>0,'删除失败']);
        }
    }

    public function member_stop()
    {
        $data=request()->get();
        $db=new AdminModel();
        $res=$db->_update($data);
        if ($res) {
            return json(['code'=>1,'成功']);
        } else {
            return json(['code'=>0,'失败']);
        }
    }
}
