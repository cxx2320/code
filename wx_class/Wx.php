<?php

class Wx
{

    /**
     * 微信授权
     * snsapi_userinfo
     * snsapi_base
     *
     */
    const AUTHORIZE = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=1#wechat_redirect';

    /**
     * 获取用户授权token
     */
    const GET_USER_ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token?';

    /**
     * 获取access_token
     */
    const GET_ACCESS_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';

    /**
     * 获取用户信息uri
     */
    const GET_USERINFO_URL = 'https://api.weixin.qq.com/sns/userinfo?';

    /**
     * 项目地址
     */
    const APP_URL = 'http://usana.huamais.com/';

    /**
     * 获取ticket
     */
    const GET_TICKET_URL = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?';

    /**
     * 模板消息发送uri
     */
    const TEMPLATE_SEND = 'https://api.weixin.qq.com/cgi-bin/message/template/send?';

    /**
     * 获取access_token
     */
    public static function getAccessToken()
    {
        $appid = config('site.wechat_app_id');
        $secret = config('site.wechat_app_secret');
        $access_token = \think\Cache::get('access_token');
        if (!$access_token) {
            $url = sprintf(self::GET_ACCESS_TOKEN, $appid, $secret);
            $token = json_decode(\fast\Http::get($url), true);
            $access_token = $token['access_token'];
            \think\Cache::set('access_token', $access_token, 7100);
        }
        return $access_token;
    }

    /**
     * 获取用户信息
     */
    public static function getUserInfo(string $code = '')
    {
        $appid = config('site.wechat_app_id');
        $secret = config('site.wechat_app_secret');

        $query = http_build_query([
            'appid' => $appid,
            'secret' => $secret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);

        $oauth2Url = self::GET_USER_ACCESS_TOKEN_URL . $query;
        $oauth2 = json_decode(\fast\Http::get($oauth2Url), true);

        $query = http_build_query([
            'access_token' => $oauth2['access_token'],
            'openid' => $oauth2['openid'],
            'lang' => 'zh_CN',
        ]);
        $get_user_info_url = self::GET_USERINFO_URL . $query;
        $userinfo = json_decode(\fast\Http::get($get_user_info_url), true);
        if (empty($userinfo['openid'])) {
            \think\Log::record(json_encode($userinfo), 'wechat_log');
        }
        
        return $userinfo;
    }

    /**
     * 获取微信jssdk权限验证配置
     *
     * @param string $url
     * @return array
     */
    public static function getJssdkConfig($url = self::APP_URL)
    {
        $data = [
            'appId' => config('site.wechat_app_id'),
            'nonceStr' => uniqid(),
            'timestamp' => time(),
            'url' => $url
        ];
        $jsapi_ticket = self::getJsapiTicket();
        $str1 = http_build_query([
            'jsapi_ticket' => $jsapi_ticket,
            'noncestr' => $data['nonceStr'],
            'timestamp' => $data['timestamp'],
            'url' => $data['url']
        ]);
        $data['signature'] = sha1($str1);
        return $data;
    }

    /**
     * 获取jsapi_ticket参数
     *
     * @return string
     */
    public static function getJsapiTicket()
    {
        $jsapi_ticket = \think\Cache::get('jsapi_ticket');
        if (!$jsapi_ticket) {
            $access_token = self::getAccessToken();
            $query = http_build_query([
                'type' => 'jsapi',
                'access_token' => $access_token
            ]);
            $url = self::GET_TICKET_URL . $query;
            $data = json_decode(\fast\Http::get($url), true);
            $jsapi_ticket = $data['ticket'];
            \think\Cache::set('jsapi_ticket', $jsapi_ticket, 7100);
        }
        return $jsapi_ticket;
    }


    /**
     * 发送模板消息
     */
    public static function templateSend()
    {
        $params = [
            'touser' => '2323231',
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

        $query = http_build_query([
            'access_token' => self::getAccessToken()
        ]);
        $uri = self::TEMPLATE_SEND . $query;
        $res = json_decode(\fast\Http::post($uri, json_encode($params)), true);
        if ($res['errcode'] != 0) {
            \think\Log::record(json_encode($res), 'wechat_log');
            return false;
        }
        return true;
    }
}
