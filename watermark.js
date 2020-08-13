// 循环页面上的图片，给图片添加水印

$(function () {
    $("img").each(function () {
        var top = $(this).offset().top + 10;//这里数字根据需要调，一般配在数据库中
        var left = $(this).offset().left + 10;
        var styleString = "width:250px;height:50px;display:block;position:absolute;left:" + left + "px;top:" + top + "px;";
        var imageUrl = "images/shuiyin.jpg";
        $(document.body).append('<div id="shuiyinDiv" style=' + styleString + '><img src=' + imageUrl + '></div>');
    });
});