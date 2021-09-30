# Postman 脚本
一些简单的脚本

## Pre-request Script

```js
// 自定义随机字符串用来测试接口(保存到环境变量)
pm.environment.set("random_word", randomString(6));

// 随机生成字符串
function randomString(len) {
    len = len || 32;
    var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz';
    var maxPos = $chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
        pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}
```
```js
// 请求数据对象输出为json字符串
function consoleJsonString(obj) {
    if (obj == undefined) {
        return;
    }
    var json = obj.reduce(function (json, item) {
        json[item.key] = item.value;
        return json;
    }, {})
    console.log(JSON.stringify(json));
}

// 请求类型字段自动转换为json用来写接口文档
var formdata = pm.request.body.formdata
consoleJsonString(formdata);
var urlencoded = pm.request.body.urlencoded
consoleJsonString(urlencoded);
```

## Tests

```js

// json响应判断code是否等于预期值
pm.test("code is 200", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.code).to.eql(200);
});
```
```js
// 可以对需要执行js的响应进行解析
pm.test("Set html", function () {
    var template = pm.response.text(); // save your html response in the template and then          
    pm.visualizer.set(template);     // set that template to pm.visualizer
})
// 可以在 Body->Visualize 选项卡查看响应结果
```