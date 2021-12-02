<?php

class Bar
{
    /**
     * 自增主键
     *
     * @var int
     */
    public $id;

    /**
     * 订单号
     *
     * @var varchar
     */
    public $order_no = '';

    /**
     * 创建人ID
     *
     * @var int
     */
    public $admin_id = 0;

    /**
     * 创建人
     *
     * @var varchar
     */
    public $admin_realname;

    /**
     * 贸易商ID
     *
     * @var int
     */
    public $custom_id = 0;

    /**
     * 贸易商名称
     *
     * @var varchar
     */
    public $custom_name = '';

    /**
     * 服务商ID
     *
     * @var int
     */
    public $store_id = 0;

    /**
     * 服务商公司名称
     *
     * @var varchar
     */
    public $company_name = '';

    /**
     * 商品分类ID
     *
     * @var int
     */
    public $goods_category_id = 0;

    /**
     * 商品分类名称
     *
     * @var varchar
     */
    public $goods_category_name = '';

    /**
     * 商品ID
     *
     * @var varchar
     */
    public $goods_ids = '';

    /**
     * 国家表ID
     *
     * @var int
     */
    public $nationality_id;

    /**
     * 国家名称
     *
     * @var varchar
     */
    public $country = '';

    /**
     * 状态//0:取消订单  1：待补充商品资料 10：待确认商品资料 11：待补充营业主体资料 20：待确认营业主体资料 21：待补充商务资料
//30:待确认商务资料  31:待报价及上传草本 40:待审核 50:待支付 60:待寄送样品 61:样品待收货 70:认证中 80: 待平台审核 90:待确认证书 100:认证完成
     *
     * @var tinyint
     */
    public $status = 1;

    /**
     * 收货地址
     *
     * @var int
     */
    public $receiving_address_id;

    /**
     * 发货地址
     *
     * @var int
     */
    public $sending_address_id;

    /**
     * 经营主体资料
     *
     * @var mixed
     */
    public $management_data;

    /**
     * 商务资料
     *
     * @var mixed
     */
    public $business_data;

    /**
     * 其他资料
     *
     * @var mixed
     */
    public $other_data;

    /**
     * 证书名称
     *
     * @var varchar
     */
    public $certificate_name = '';

    /**
     * 服务类型
     *
     * @var varchar
     */
    public $service_type = '';

    /**
     * 产品ID
     *
     * @var int
     */
    public $sh_product_id = 0;

    /**
     * 产品类型 1初审，2年审
     *
     * @var tinyint
     */
    public $product_type;

    /**
     * 产品名称
     *
     * @var varchar
     */
    public $product_name = '';

    /**
     * 产品周期：天
     *
     * @var int
     */
    public $product_cycle = 0;

    /**
     * 证书草本
     *
     * @var varchar
     */
    public $certificate_sample = '';

    /**
     * 是否需要寄送样品
     *
     * @var tinyint
     */
    public $is_prototype = 0;

    /**
     * 不通过原因
     *
     * @var tinytext
     */
    public $remark;

    /**
     * 币种
     *
     * @var varchar
     */
    public $currency = '';

    /**
     * 预估价格
     *
     * @var decimal
     */
    public $estimated_price;

    /**
     * 订单实际价格
     *
     * @var decimal
     */
    public $amount;

    /**
     * 证书
     *
     * @var varchar
     */
    public $certificate = '';

    /**
     * 质检报告
     *
     * @var varchar
     */
    public $quality_inspection_report = '';

    /**
     * 第三方流水号
     *
     * @var varchar
     */
    public $out_trade_no = '';

    /**
     * 物流单号
     *
     * @var varchar
     */
    public $logistics_order_no = '';

    /**
     * 物流公司名称
     *
     * @var varchar
     */
    public $logistics_name = '';

    /**
     * 报价截止日期
     *
     * @var int
     */
    public $estimated_price_time = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time = 0;

    /**
     * 支付方式0未知1支付宝2微信3银联 4线下
     *
     * @var tinyint
     */
    public $pay_type;

    /**
     * 支付时间
     *
     * @var int
     */
    public $pay_time = 0;

    /**
     * 寄送样品时间
     *
     * @var int
     */
    public $sending_time = 0;

    /**
     * 上传证书时间
     *
     * @var int
     */
    public $cert_time = 0;

    /**
     * 平台审核时间
     *
     * @var int
     */
    public $check_time = 0;

    /**
     * 完成认证时间
     *
     * @var int
     */
    public $order_settlement_time = 0;

    /**
     * 删除时间
     *
     * @var int
     */
    public $delete_time = 0;

}