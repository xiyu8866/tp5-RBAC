<?php
namespace app\admin\controller;

use app\admin\model\AdminModel;
use think\Controller;

class Login extends Controller
{
    public function index()
    {
        return view('login');
    }
    public function action()
    {
        $data=request()->param();
        $db=new AdminModel();
        $info=$db->where('user', trim($data['username']))->find();
        if($info['status']!=1){
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
    public function logout()
    {
        session('user', null);
        session('ids', null);
        $this->redirect('Login/index');
    }
}
