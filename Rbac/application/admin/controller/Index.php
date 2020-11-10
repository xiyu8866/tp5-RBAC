<?php
namespace app\admin\controller;

use app\admin\model\AdminModel;
use think\Db;
use think\facade\App;

class Index extends Base
{
    public function index()
    {
        return view('index');
    }
    public function welcome()
    {
        $version = Db::query("select version() as ver");
        $mysql_version =  $version[0]['ver'];
        $this->assign(["mysql_version"=> $mysql_version,'tp_version'=>App::version()]);
        return view('welcome');
    }
    public function switchLogin()
    {
        return view('switchLogin');
    }
    public function login()
    {
        $data=request()->post();
        $db=new AdminModel();
        $info=$db->where('user', trim($data['username']))->find();
        if ($info['status']!=1) {
            return json(['code'=>0,'msg'=>'用户已停用']);
        }
        if (!$info) {
            return json(['code'=>0,'msg'=>'用户不存在']);
        }
        if ($info['pass'] !== md5($data['password'])) {
            return json(['code'=>0,'msg'=>'用户名或密码错误']);
        }
        session('ids', $info['id']);
        session('user', $info['user']);
        return json(['code'=>1,'mag'=>'登录成功']);
    }
}
