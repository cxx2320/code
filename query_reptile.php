<?php

/*
 * This file is part of the mingzaily/lumen-permission.
 *
 * (c) mingzaily <mingzaily@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use QL\QueryList;

$url   = 'https://maoyan.com/films/1218273';
$rules = [
    'cover' => ['.banner .avatar-shadow>img', 'src'],
    'level' => ['.banner .movie-stats-container .movie-index:eq(0) .star-on', 'style', '', function ($level) {
        $patterns = "/\d+/";
        preg_match_all($patterns, $level, $arr);

        return $arr[0][0] / 10;
    }],
    'num'       => ['.banner .movie-stats-container .movie-index:eq(0) .stonefont:eq(1)', 'text'],
    'piaofang'  => ['.banner .movie-stats-container .movie-index:eq(1) .stonefont', 'text'],
    'unit'      => ['.banner .movie-stats-container .movie-index:eq(1) .unit', 'text'],
    'title'     => ['.banner h3', 'text'],
    'as_title'  => ['.banner .ename', 'text'],
    'des'       => ['#app .dra', 'text'],
    'type'      => ['.banner ul li:eq(0)', 'text'],
    'time'      => ['.banner ul li:eq(1)', 'text'],
    'fang_time' => ['.banner ul li:eq(2)', 'text'],
    'img'       => ['.album', 'html'],
    'yanyuan'   => ['.tab-content-container .celebrity-container .celebrity-group:eq(1）ul', 'html'],
    'daoyan'    => ['.tab-content-container .celebrity-container .celebrity-group:eq(0）ul', 'html'],
    'comment'   => ['.comment-list-container', 'html'],
];
$client = new GuzzleHttp\Client();
$res    = $client->request('GET', $url, [
    'headers' => [
        'Accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Encoding'           => 'gzip, deflate, br',
        'Accept-Language'           => 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
        'Cache-Control'             => 'no-cache',
        'Connection'                => 'keep-alive',
        'Cookie'                    => '__mta=89019172.1596106656819.1596106656819.1596106657104.2; uuid_n_v=v1; uuid=75A7EF40D25311EAB807BDA3D83E4DB48EDF2D828EA14E37974917569C7C835B; _csrf=b75fd48486b95f282bb2a801862409aa5d06c0b67b90c221cf0f4968456effe3; Hm_lvt_703e94591e87be68cc8da0da7cbd0be2=1596106652; Hm_lpvt_703e94591e87be68cc8da0da7cbd0be2=1596106657; mojo-uuid=a6c5e175ebd3c033301480b9dc3ecddb; mojo-trace-id=3; mojo-session-id={"id":"b09f723c92a486100e0497c4200f1382","time":1596106654419}; _lx_utm=utm_source%3DBaidu%26utm_medium%3Dorganic; _lxsdk_cuid=1739f5ec6f0c8-048d5cc89c07938-4c302372-1fa400-1739f5ec6f0c8; _lxsdk_s=1739f5ec6f1-818-549-900%7C%7C4; _lxsdk=75A7EF40D25311EAB807BDA3D83E4DB48EDF2D828EA14E37974917569C7C835B; __mta=89019172.1596106656819.1596106656819.1596106656819.1',
        'Pragma'                    => 'no-cache',
        'Host'                      => 'maoyan.com',
        'Referer'                   => 'https://maoyan.com/',
        'Upgrade-Insecure-Requests' => 1,
        'User-Agent'                => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:78.0) Gecko/20100101 Firefox/78.0',
    ],
]);
$html = (string) $res->getBody();

$data = QueryList::html($html)->rules($rules)->query()->getData(function ($item) {
    $item['img'] = QueryList::html($item['img'], [
        'img' => ['.default-img', 'data-src'],
    ])->data;

    $item['yanyuan'] = QueryList::html($item['yanyuan'], [
        'cover' => ['li>a .default-img', 'data-src'],
        'name'  => ['li>.info .name', 'text'],
        'role'  => ['li>.info .role', 'text'],
    ])->data;

    $item['daoyan'] = QueryList::html($item['daoyan'], [
        'cover' => ['li a .default-img', 'data-src'],
        'name'  => ['li .info a', 'text'],
    ])->data;

    $item['comment'] = QueryList::html($item['comment'], [
        'img'   => ['ul li .portrait-container img', 'src'],
        'name'  => ['ul li .main .user .name', 'text'],
        'time'  => ['ul li .main .time', 'title'],
        'level' => ['ul li .main .time ul', 'data-score', '', function ($level) {
            return floor($level / 2);
        }],
        'content' => ['ul li .main .comment-content', 'text'],
    ])->data;

    return $item;
});
var_dump($data->all());
