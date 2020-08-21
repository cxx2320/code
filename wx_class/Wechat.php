<?php

namespace fast;

class Wechat
{
    const API_SEND_TEMPLATE = 'https://api.weixin.qq.com/cgi-bin/message/template/send';
    const GET_ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    const GET_USERINFO_URL = 'https://api.weixin.qq.com/sns/userinfo';
    const GET_Ticket_URL = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
    const APP_URL = 'https://dd.daobentech.net'; //项目地址

    //获取access_token
    public static function getAccessToken()
    {
        $appid = config('site.wechat_app_id');
        $secret = config('site.wechat_app_secret');
        $access_token = \think\Cache::get('access_token');

        if (!$access_token) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
            $token = json_decode(\fast\Http::get($url), true);
            $access_token = $token['access_token'];
            \think\Cache::set('access_token', $access_token, 7100);
        }
        return $access_token;
    }

    public static function getUserInfo(string $code = '')
    {
        $appid = config('site.wechat_app_id');
        $secret = config('site.wechat_app_secret');
        $access_token = self::getAccessToken();
        $oauth2Url = self::GET_ACCESS_TOKEN_URL . '?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $oauth2 = json_decode(\fast\Http::get($oauth2Url), true);
        $get_user_info_url = self::GET_USERINFO_URL . '?access_token='.$oauth2['access_token'].'&openid=' . $oauth2['openid'] . '&lang=zh_CN';
        $userinfo = json_decode(\fast\Http::get($get_user_info_url), true);
        if (empty($userinfo['openid'])) {
            \think\Log::record(json_encode($userinfo), 'wechat_get_userinfo');
        }
        
        return $userinfo;
    }

    /**
     * 发送模板消息
     */
    public static function sendTemplate($params):? bool
    {

        //异步发送消息
        // $res_data = \fast\Http::sendAsyncRequest($uri,json_encode($params),'POST');
        // return $res_data;

        $access_token = self::getAccessToken();
        $uri = self::API_SEND_TEMPLATE.'?access_token='.$access_token;
        $res_data = \fast\Http::post($uri, json_encode($params));
        $res_data = json_decode($res_data, true);
        if ($res_data['errcode'] != 0) {
            \think\Log::record(json_encode($res_data), 'wechat_template');
            return false;
        }
        return true;
    }

    /**
     * 发送模板消息 test
     *
     */
    public static function sendMsg($openid)
    {
        if ($openid == '') {
            \think\Log::record('无openid', 'wechat_template');
            return false;
        }
        $params = [
            'touser' => $openid,
            'template_id' => '02S_3iEZ4IYFrg5a5mZV5ra7UQ4tjajp41e77uZYLXA',
            'url'    => 'http://www.baidu.com',
            'miniprogram' => '',
            'data'  => [
                'first' => [
                    'value' => 'first',
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => 'keyword1',
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => 'keyword2',
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => 'remark',
                    'color' => '#173177'
                ]
            ]
        ];
        return self::sendTemplate($params);
    }

    /**
     * 电工接单成功 给下单用户发送消息
     *
     */
    public static function sendMsgReceipt($openid = '', $worker_name = '', $phone = '')
    {
        if ($openid == '') {
            \think\Log::record('无openid', 'wechat_template');
            return false;
        }
        $params = [
            'touser' => $openid,
            'template_id' => 'HpUhhPg8QWGkRIYqThHacykjlCUtcn9M8lPSo6E3nQw',
            'url'    => self::APP_URL.'/pages/order/list/list',
            'miniprogram' => '',
            'data'  => [
                'first' => [
                    'value' => '您的订单已成功接单!',
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $worker_name,
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $phone,
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '您的订单已被接单，点击详情查看详细信息!',
                    'color' => '#173177'
                ]
            ]
        ];
        
        return self::sendTemplate($params);
    }

    /**
     * 后台分配电工，给电工发送消息
     *
     */
    public static function sendMsgDistribution($openid = '', $user_name = '', $time = '')
    {
        if ($openid == '') {
            \think\Log::record('无openid', 'wechat_template');
            return false;
        }
        $params = [
            'touser' => $openid,
            'template_id' => 'w1x0Fv91YxSCjZdvuv_48fYQ1j9V0HwnvTDHfORKjCU',
            'url'    => self::APP_URL.'/pages/order/list/list',
            'miniprogram' => '',
            'data'  => [
                'first' => [
                    'value' => '您有一个新订单待处理!',
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $user_name,
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $time,
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '点击查看订单详情!',
                    'color' => '#173177'
                ]
            ]
        ];
        return self::sendTemplate($params);
    }

    /**
     * 认证结果提醒模板消息
     *
     */
    public static function sendReason($openid = '', $nickname = '', $state = 0, $reason = '')
    {
        if (!in_array($state, ['2','3'])) {
            return true;
        }
        if ($openid == '') {
            \think\Log::record('无openid', 'wechat_template');
            return false;
        }
        $params = [
            'touser' => $openid,
            'template_id' => 'soV-9zuSvhYZ5AKiV1WFJPHcMETe1zxYJCQLyrf240o',
            'url'    => self::APP_URL.'/pages/index/index',
            'miniprogram' => '',
            'data'  => [
                'first' => [
                    'value' => '您的审核结果有了更新！',
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $nickname,
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $state == 2 ? '通过！' : '驳回！',
                    'color' => '#173177'
                ],
                'keyword3' => [
                    'value' => date('Y-m-d H:i', time()),
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => $state == 2 ? '你提交的审核信息已经通过！' : $reason ?? '无',
                    'color' => '#173177'
                ]
            ]
        ];
        return self::sendTemplate($params);
    }

    /**
     * 获取微信jssdk权限验证配置
     *
     * @param string $url
     * @return array
     */
    public static function getJssdkConfig($url = self::APP_URL) :?array
    {
        $data = [
            'appId' => config('site.wechat_app_id'),
            'nonceStr' => uniqid(),
            'timestamp' => time(),
            'url' => $url
        ];
        $jsapi_ticket = self::getJsapiTicket();
        $str1 = 'jsapi_ticket='. $jsapi_ticket .'&noncestr='.$data['nonceStr'].'&timestamp='.$data['timestamp'].'&url='.$data['url'];
        $data['signature'] = sha1($str1);
        return $data;
    }

    /**
     * 获取jsapi_ticket参数
     *
     * @return string
     */
    public static function getJsapiTicket() :?string
    {
        $jsapi_ticket = \think\Cache::get('jsapi_ticket');
        if (!$jsapi_ticket) {
            $access_token = self::getAccessToken();
            $url = self::GET_Ticket_URL . '?type=jsapi&access_token=' . $access_token;
            $data = json_decode(\fast\Http::get($url), true);
            $jsapi_ticket = $data['ticket'];
            \think\Cache::set('jsapi_ticket', $jsapi_ticket, 7100);
        }
        return $jsapi_ticket;
    }
}
