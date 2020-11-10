<?php
namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\admin\model\RoleModel;
use app\admin\model\RuleModel;
use think\Controller;

class Base extends Controller
{
    protected function initialize()
    {
        parent::initialize();
        if (!session('user')) {
            $this->redirect('Login/index');
        }
        //当前角色
        $role=(new AdminModel())->where('user', session('user'))->value('roleId');
        //当前角色拥有的权限的id
        $rule=(new RoleModel())->where('RoleId', $role)->value('ruleId');
        //当前控制器
        $controller=request()->controller();
        //当前方法
        $action=request()->action();
        if ($rule=='*') {
            $auth=(new RuleModel())->where(['pid'=>0,'type'=>1])->select();
            $map=[];
            $list=$this->getTree($auth, $map);
        } else {
            //获取整个权限节点
            $list=(new RuleModel())->where('RuleId', 'in', $rule)->where('type', 1)->select()->toArray();
            foreach ($list as $k=>$v) {
                if ($v['pid']==0) {
                    $auth[]=$v;
                }
            }
            $rule=explode(',', $rule);
            //当前 节点 的id
            $ruId=(new RuleModel())->where('rules', $controller.'/'.$action)->value('ruleId');
            //判断当前角色是否有权限访问该方法
            if (!in_array($ruId, $rule)) {
                $this->redirect('/error/error.html');
            }
            //获取左侧树状导航
            $map['RuleId']=$rule;
            $list=$this->getTree($auth, $map);
        }
        $this->assign('auth', $list);
    }

    /**
     * 权限树状结构
     *
     * @param [type] $list
     * @param [type] $map
     * @return void
     */
    public function getTree($list, $map)
    {
        $db=new RuleModel();
        foreach ($list as $k=>$v) {
            //二级
            $map['pid']=$v['RuleId'];
            $info=$db->where($map)->order('sort')->select()->toArray();
            //赋值
            $list[$k]['child']=$info;
            foreach ($info as $key=>$val) {
                $map['pid']=$v['RuleId'];
                $is_id=$db->where('pid', $val['RuleId'])->value('RuleId');
                if ($is_id) {
                    $list[$k]['child'] = $this->getTree($info, $map);
                }
            }
        }
        return $list;
    }
}
