<?php

use think\Request;

class Miniapp
{
    /**
     * 登录凭证校验。通过 wx.login 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录流程。更多使用方法详见 小程序登录。
     */
    const AUTH_CODE2SESSION = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';

    /**
     * 获取access_tokenuri
     */
    const ACCESS_TOKEN_URI = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';

    /**
     * 获取数量较多的小程序码uri 适用于需要的码数量极多的业务场景
     */
    const UNLIMIT_URI = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=%s';

    /**
     * 获取SessionKey
     * @param string $code 微信小程序code
     * @return array
     * @throws Exception
     */
    public static function getSessionKey($code = '')
    {
        if (empty($code)) {
            throw new \Exception('code不合法');
        }
        $result = \fast\Http::get(sprintf(self::AUTH_CODE2SESSION, config('site.miniapp_id'), config('site.app_secret'), $code));
        $jscode2session = json_decode($result, true);

        if (isset($jscode2session['errcode']) && $jscode2session['errcode'] != 0) {
            throw new \Exception('服务器错误，请重试！');
            // throw new \Exception($jscode2session['errcode'].' : '.$jscode2session['errmsg']);
        }
        return $jscode2session;
    }

    /**
     * 获取access_token
     */
    public static function getAccessToken()
    {
        $access_token = \think\Cache::get('access_token');
        if (!$access_token) {
            $appid = config('site.miniapp_id');
            $secret = config('site.app_secret');
            $token = json_decode(\fast\Http::get(sprintf(self::ACCESS_TOKEN_URI, $appid, $secret)), true);
            $access_token = $token['access_token'];
            \think\Cache::set('access_token', $access_token, 7100);
        }
        return $access_token;
    }

    /**
     * 获取小程序码
     * @link https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
     * @param array $data 微信生成小程序码需要的参数
     * @return array|boole
     */
    public static function getUnlimited($data = [])
    {
        if (empty($data)) {
            return false;
        }
        $token = self::getAccessToken();
        $data = \fast\Http::post(sprintf(self::UNLIMIT_URI, $token), json_encode($data));
        return json_decode($data, true);
    }

    /**
     * 获取用户信息
     * @throws \Exception
     */
    public static function getUserInfo($code = '', $encryptedData = '', $iv = '')
    {
        if (empty($code)) {
            throw new \Exception('参数错误');
        }
        $jscode2session = self::getSessionKey($code);
        $encryptedData =  $encryptedData ?: Request::instance()->request('data');
        $iv = $iv ?: Request::instance()->request('iv');
        $pc = new WXBizDataCrypt(config('site.miniapp_id'), $jscode2session['session_key']);
        $data = null;
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode != 0) {
            throw new \Exception($errCode);
        }
        return json_decode($data, true);
    }

    /**
     * 获取小程序码
     * @link https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
     * @param array $data 微信生成小程序码需要的参数
     * @return array|boole
     */
    public static function getUnlimited($data = [])
    {
        if (empty($data)) {
            return false;
        }
        $token = self::getAccessToken();
        $data = \fast\Http::post(sprintf(self::UNLIMIT_URI, $token), json_encode($data));
        return json_decode($data, true);
    }
}
