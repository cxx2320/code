<?php

function has(string $operator = '>=', int $count = 1, string $id = '*', string $joinType = '', Query $query = null): Query
{
    // 对这些变量进行具体值代入
    // $foreignKey sh_supply_plan_goods_id
    // $localKey id
    $model         = Str::snake(class_basename($this->parent)); // SupplyPlanGoodsModel 
    $throughTable  = $this->through->getTable(); // sh_cert_goods
    $pk            = $this->throughPk; // id
    $throughKey    = $this->throughKey; // sh_cert_goods_id
    $relation      = new $this->model; // app\common\model\goods\CertModel
    $relationTable = $relation->getTable(); // sh_cert
    $softDelete    = $this->query->getOptions('soft_delete');

    if ('*' != $id) {
        $id = $relationTable . '.' . $relation->getPk();
    }
    $query = $query ?: $this->parent->db()->alias($model);

    return $query->field($model . '.*')
        ->join($throughTable, $throughTable . '.' . $this->foreignKey . '=' . $model . '.' . $this->localKey)
        ->join($relationTable, $relationTable . '.' . $throughKey . '=' . $throughTable . '.' . $this->throughPk)
        ->when($softDelete, function ($query) use ($softDelete, $relationTable) {
            $query->where($relationTable . strstr($softDelete[0], '.'), '=' == $softDelete[1][0] ? $softDelete[1][1] : null);
        })
        ->group($relationTable . '.' . $this->throughKey)
        ->having('count(' . $id . ')' . $operator . $count);
}

// 分析组合后的sql，矫正上面的变量
$query->field('SupplyPlanGoodsModel.*')
    ->join('sh_cert_goods', 'sh_cert_goods.sh_supply_plan_goods_id=SupplyPlanGoodsModel.id')
    ->join('sh_cert', 'sh_cert.sh_cert_goods_id=sh_cert_goods.id')
    ->group('sh_cert.sh_cert_goods_id')
    ->select();
