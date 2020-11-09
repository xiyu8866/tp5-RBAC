<?php
namespace app\admin\controller;

use app\admin\model\RuleModel;

class Rule extends Base
{
    public function index()
    {
        $db=new RuleModel();
        $pid=$db->where('pid', 0)->order('sort asc')->select()->toArray();
        $info=$this->getTree($pid, $map=[]);
        return view('adminRule', ['info'=>$info]);
    }

    public function add()
    {
        $id=request()->get('id');
        $db=new RuleModel();
        $pid=$db->where('RuleId', $id)->find();
        return view('ruleAdd', ['pid'=>$pid]);
    }

    public function save()
    {
        $data = request()->post();
        $db=new RuleModel();
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
        $db=new RuleModel();
        $info=$db->where('RuleId', $id)->find();
        if ($info['pid']==0) {
            $pName='顶级栏目';
        } else {
            $pName=$db->where('RuleId', $info['pid'])->value('name');
        }
        $pid=['RuleId'=>$info['pid'],'name'=>$pName];
        return view('ruleEdit', ['info'=>$info,'pid'=>$pid]);
    }

    public function delete()
    {
        $id = request()->get('id');
        $db=new RuleModel();
        $ids=$this->getIds($id);
        $res=$db->where('RuleId', 'in', $ids)->delete();
        if ($res) {
            return json(['code'=>1,'删除成功']);
        } else {
            return json(['code'=>0,'删除失败']);
        }
    }

    public function getIds($id)
    {
        $db=new RuleModel();
        $ids=$db->where('pid', $id)->column('RuleId');
        $newIds=[];
        if (is_array($ids)) {
            foreach ($ids as $k=>$v) {
                $newIds[]=$db->where('pid', $v)->column('RuleId');
            }
            foreach ($newIds as $ker=>$val) {
                $ids=array_merge($ids, $val);
            }
        }
        array_push($ids, $id);
        return $ids;
    }
}
