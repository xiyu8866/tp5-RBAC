<?php
namespace app\admin\model;

use app\common\model\BaseModel;

class RoleModel extends BaseModel
{
    protected $table='x_roles';
    protected $pk='RoleId';
    protected $autoWriteTimestamp = true;

    public function _update($data)
    {
        $data['ruleId']=implode(',', $data['ruleId']);
        if (!isset($data[$this->pk])) {
            return $this->save($data);
        } else {
            return $this->save($data, [$this->pk=>$data[$this->pk]]);
        }
    }
}
