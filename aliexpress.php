<?php
error_reporting(E_ALL);

require_once("./vendor/autoload.php");

// $origin_url = 'https://www.aliexpress.com/item/1005003777500537.html?pdp_ext_f=%7B%22ship_from%22:%22CN%22,%22sku_id%22:%2212000027133984624%22%7D&sourceType=fd&&scm=1007.28480.273940.0&scm_id=1007.28480.273940.0&scm-url=1007.28480.273940.0&pvid=a15081fb-e4d7-4efd-99d7-e40e8206c7c9&utparam=%257B%2522process_id%2522%253A%2522110%2522%252C%2522x_object_type%2522%253A%2522product%2522%252C%2522pvid%2522%253A%2522a15081fb-e4d7-4efd-99d7-e40e8206c7c9%2522%252C%2522belongs%2522%253A%255B%257B%2522id%2522%253A%252231523521%2522%252C%2522type%2522%253A%2522dataset%2522%257D%255D%252C%2522pageSize%2522%253A%252210%2522%252C%2522language%2522%253A%2522en%2522%252C%2522scm%2522%253A%25221007.28480.273940.0%2522%252C%2522countryId%2522%253A%2522CN%2522%252C%2522scene%2522%253A%2522TopSelection-Waterfall%2522%252C%2522tpp_buckets%2522%253A%252221669%25230%2523265320%252347_21669%25234190%252319159%2523101_15324%25230%2523132599%252310%2522%252C%2522x_object_id%2522%253A%25221005003777500537%2522%257D&pdp_npi=2%40dis%21USD%21US%20%24122.17%21US%20%2417.73%21%21%21%21%21%400b015e7016602071353211690ee7d3%2112000027133984624%21gsd&spm=a2g0o.11810135.productlist.7';
$origin_url = $_GET['url'] ?? '';

$html = file_get_contents($origin_url);
// file_put_contents('./test.html', $html);
$html = str_replace("\n", " ", $html);

$result = get_need_between($html, 'runParams = ', "var GaData = ");
$result = rtrim($result, '; ');
$result = format_json($result);

$current_produce_attr = $result['data']['skuModule']['selectedSkuAttr'] ?? '';
$current_produce = [];

foreach ($result['data']['skuModule']['skuPriceList'] ?? [] as $value) {
    if ($value['skuAttr'] == $current_produce_attr) {
        $current_produce = $value;
        break;
    }
}

?>

<form action="" method="get">
    链接：<input type="text" name="url">
    <input type="submit" value="提交">
</form>

<?php

echo '名称：' . ($result['data']['titleModule']['subject'] ?? '-') . "<br>";
echo '价格：'
    . ($current_produce['skuVal']['skuActivityAmount']['value'] ?? $current_produce['skuVal']['skuAmount']['value'] ?? '-')
    . "<br>";
echo '库存：' . ($current_produce['skuVal']['availQuantity'] ?? '-') . "<br>";

echo '销量：' . ($result['data']['titleModule']['tradeCount'] ?? '-') . "<br>";
echo '价格区间：'
    . ($result['data']['priceModule']['minActivityAmount']['value'] ?? $result['data']['priceModule']['minAmount']['value'] ?? '-')
    . '-'
    . ($result['data']['priceModule']['maxActivityAmount']['value'] ?? $result['data']['priceModule']['maxAmount']['value'] ?? '-')
    . "<br>";
echo '总库存：' . ($result['data']['actionModule']['totalAvailQuantity'] ?? '-') . "<br>";

/**
 * 解析js字面量对象
 * 
 * @param string $json
 * @return array
 */
function format_json(string $json)
{
    $json = preg_replace("/\s(?=\s)/", "\\1", $json); // 多个空格只留一个
    $json = preg_replace("/([\{\}\,]+)\s?'?\s?(\w*?)\s?'?\s?:\s?/", '\\1"\\2":', $json); // 转义key
    $json = preg_replace("/\"\s?:\'\s?(.*?)\'\s?([\,\]\}]+?)/", '":"\\1"\\2', $json); // 转义 value
    return json_decode($json, true);
}

/**
 * 获取字符串开始与结束之间的字符串
 *
 * @param string $string
 * @param string $mark1 开始字符串
 * @param string $mark2 结束字符串
 * @return string
 */
function get_need_between(string $string, string $mark1, string $mark2)
{
    $start = mb_stripos($string, $mark1);
    $end = mb_stripos($string, $mark2);
    if ($start === false || $end === false || $start >= $end) {
        return '';
    }
    $new_string = mb_substr($string, $start + mb_strlen($mark1), $end - $start - mb_strlen($mark1));
    return $new_string;
}
