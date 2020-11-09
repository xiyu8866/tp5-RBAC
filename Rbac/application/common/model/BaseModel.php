<?php
namespace app\common\model;

use think\Model;

class BaseModel extends Model
{
    protected $pk;
    protected $table;

    protected function initialize()
    {
        parent::initialize();
        $this->pk = $this->getPk();
        $this->table = $this->getTable();
    }
    /**
     * 添加/修改
     *
     * @param [type] $data
     * @return void
     * 添加或修改数据
     */
    public function _update($data)
    {
        if (!isset($data[$this->pk])) {
            return $this->save($data);
        } else {
            return $this->save($data, [$this->pk=>$data[$this->pk]]);
        }
    }
}
