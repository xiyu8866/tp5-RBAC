<?php
namespace app\admin\model;

use app\common\model\BaseModel;

class RuleModel extends BaseModel
{
    protected $table='x_rules';
    protected $pk='RuleId';
    protected $autoWriteTimestamp = true;
}
