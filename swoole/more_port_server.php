<?php

/**
 * 多端口监听
 */

$http = new Swoole\Http\Server("127.0.0.1", 2589);
$http->on('request', function ($request, $response) {
    $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
});
$http->on('WorkerStart', function () {
    var_dump(2);
});

/**
 * 子服务默认继承主服务的on和set设置（可以通过单独设置子服务的on和set方法覆盖主服务）
 * 子服务是属于主服务下的，不能使用worker相关的设置
 * 可以通过主服务的 $http->ports 成员变量获取所有的服务
 */
$sub_server = $http->addListener("127.0.0.1", 2586, SWOOLE_SOCK_TCP);
$sub_server->on('request', function ($request, $response) {
    $response->end("<h1>Hello Sub. #" . rand(1000, 9999) . "</h1>");
});


$http->start();
