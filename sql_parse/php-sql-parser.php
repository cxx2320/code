<?php

use PHPSQLParser\PHPSQLParser;

require './vendor/autoload.php';

$sql = <<<SQL
CREATE TABLE `rt_sh_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `order_no` varchar(100) NOT NULL DEFAULT '' COMMENT '订单号',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建人ID',
  `admin_realname` varchar(50) NOT NULL COMMENT '创建人',
  `custom_id` int(11) NOT NULL DEFAULT '0' COMMENT '贸易商ID',
  `custom_name` varchar(100) NOT NULL DEFAULT '' COMMENT '贸易商名称',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '服务商ID',
  `company_name` varchar(50) NOT NULL DEFAULT '' COMMENT '服务商公司名称',
  `goods_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类ID',
  `goods_category_name` varchar(50) NOT NULL DEFAULT '' COMMENT '商品分类名称',
  `goods_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '商品ID',
  `nationality_id` int(11) NOT NULL COMMENT '国家表ID',
  `country` varchar(50) NOT NULL DEFAULT '' COMMENT '国家名称',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态//0:取消订单  1：待补充商品资料 10：待确认商品资料 11：待补充营业主体资料 20：待确认营业主体资料 21：待补充商务资料\n//30:待确认商务资料  31:待报价及上传草本 40:待审核 50:待支付 60:待寄送样品 61:样品待收货 70:认证中 80: 待平台审核 90:待确认证书 100:认证完成',
  `receiving_address_id` int(11) NOT NULL COMMENT '收货地址',
  `sending_address_id` int(11) NOT NULL COMMENT '发货地址',
  `management_data` json DEFAULT NULL COMMENT '经营主体资料',
  `business_data` json DEFAULT NULL COMMENT '商务资料',
  `other_data` json DEFAULT NULL COMMENT '其他资料',
  `certificate_name` varchar(50) NOT NULL DEFAULT '' COMMENT '证书名称',
  `service_type` varchar(50) NOT NULL DEFAULT '' COMMENT '服务类型',
  `sh_product_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品ID',
  `product_type` tinyint(1) NOT NULL COMMENT '产品类型 1初审，2年审',
  `product_name` varchar(50) NOT NULL DEFAULT '' COMMENT '产品名称',
  `product_cycle` int(11) NOT NULL DEFAULT '0' COMMENT '产品周期：天',
  `certificate_sample` varchar(255) NOT NULL DEFAULT '' COMMENT '证书草本',
  `is_prototype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否需要寄送样品',
  `remark` tinytext NOT NULL COMMENT '不通过原因',
  `currency` varchar(20) NOT NULL DEFAULT '' COMMENT '币种',
  `estimated_price` decimal(8,2) NOT NULL COMMENT '预估价格',
  `amount` decimal(8,2) NOT NULL COMMENT '订单实际价格',
  `certificate` varchar(255) NOT NULL DEFAULT '' COMMENT '证书',
  `quality_inspection_report` varchar(255) NOT NULL DEFAULT '' COMMENT '质检报告',
  `out_trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '第三方流水号',
  `logistics_order_no` varchar(100) NOT NULL DEFAULT '' COMMENT '物流单号',
  `logistics_name` varchar(50) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `estimated_price_time` int(11) NOT NULL DEFAULT '0' COMMENT '报价截止日期',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `pay_type` tinyint(4) NOT NULL COMMENT '支付方式0未知1支付宝2微信3银联 4线下',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `sending_time` int(11) NOT NULL DEFAULT '0' COMMENT '寄送样品时间',
  `cert_time` int(11) NOT NULL DEFAULT '0' COMMENT '上传证书时间',
  `check_time` int(11) NOT NULL DEFAULT '0' COMMENT '平台审核时间',
  `order_settlement_time` int(11) NOT NULL DEFAULT '0' COMMENT '完成认证时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=521 DEFAULT CHARSET=utf8;
SQL;

/**
 * 把sql语句解析成对象
 * @todo 完成数据库类型和php数据类型映射
 */
class SqlToObject
{
    /**
     * @var PHPSQLParser
     */
    private $parser;

    private $class_tpl = <<<TPL
    <?php
    
    class {%classname%}
    {
    {%content%}}
    TPL;

    public function __construct()
    {
        $this->parser = new PHPSQLParser();
    }

    /**
     * 解析sql
     *
     * @param string $sql
     * @param string $class_name
     * @return string
     */
    public function parse(string $sql, string $class_name = 'Bar')
    {
        $sql_array = $this->sqlParse($sql);
        $attribute = $this->buildAttribute($sql_array);
        return $this->replace($this->class_tpl, [
            'classname' => $class_name,
            'content' => $attribute,
        ]);
    }

    /**
     * 绑定属性
     *
     * @param array $attributes
     * @return string
     */
    private function buildAttribute($attributes = []): string
    {
        $string = '';
        foreach ($attributes as $field) {
            $tpl = '    /**';
            if ($field['comment']) {
                $tpl .= PHP_EOL . '     * {%comment%}';
                $tpl .= PHP_EOL . '     *';
            }
            $tpl .= PHP_EOL . '     * @var {%type%}';
            $tpl .= PHP_EOL . '     */';
            if ($field['default_value'] !== null) {
                $tpl .= PHP_EOL . '    public ${%name%} = {%default_value%};';
            } else {
                $tpl .= PHP_EOL . '    public ${%name%};';
            }
            $tpl .= PHP_EOL;
            $tpl .= PHP_EOL;
            $string .= $this->replace($tpl, $field);
        }
        return $string;
    }

    /**
     * 模板替换
     *
     * @param string $tpl
     * @param array $replace
     * @return string
     */
    public function replace($tpl = '', array $replace = [])
    {
        $search = [];
        foreach ($replace as $key => $item) {
            $search[] = '{%' . $key . '%}';
        }
        return str_replace($search, $replace, $tpl);
    }

    public function sqlParse(string $sql)
    {
        $parsed = $this->parser->parse($sql);
        if (!isset($parsed['TABLE'])) {
            throw new Exception('error sql');
        }
        $sql_array = [];
        foreach ($parsed['TABLE']['create-def']['sub_tree'] as $column) {
            if ($column['expr_type'] !== 'column-def') {
                continue;
            }
            $field = [];
            foreach ($column['sub_tree'] as $tree) {
                if ($tree['expr_type'] === 'colref') {
                    $field['name'] = $tree['base_expr'];
                    continue;
                }
                if ($tree['expr_type'] === 'column-type') {
                    foreach ($tree['sub_tree'] as $column_type) {
                        if ($column_type['expr_type'] === 'data-type') {
                            $field['type'] = $column_type['base_expr'];
                        }
                        if ($column_type['expr_type'] === 'comment') {
                            $field['comment'] = $column_type['base_expr'];
                        }
                        if ($column_type['expr_type'] === 'default-value') {
                            $field['default_value'] = $column_type['base_expr'];
                        }
                    }
                }
            }
            if ($field) {
                $sql_array[] = $field;
            }
        }

        return array_map(function ($item) {
            $default_value = isset($item['default_value'])
                ? str_replace('\'', '', $item['default_value'])
                : null;
            return [
                'type' => $item['type'] ?? 'mixed',
                'comment' => str_replace('\'', '', $item['comment'] ?? ''),
                'name' => str_replace('`', '', $item['name']),
                'default_value' => $default_value === '' ? "''" : $default_value
            ];
        }, $sql_array);
    }
}

$sql_to_object = new SqlToObject();
$class_string = $sql_to_object->parse($sql);
file_put_contents('./class.php', $class_string);
