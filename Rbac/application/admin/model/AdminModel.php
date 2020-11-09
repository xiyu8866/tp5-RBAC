<?php
namespace app\admin\model;

use app\common\model\BaseModel;

class AdminModel extends BaseModel
{
    protected $table = 'x_admin';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    public function setPassAttr($value)
    {
        return md5($value);
    }
}
