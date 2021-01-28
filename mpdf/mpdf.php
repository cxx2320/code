<?php

require_once "./vendor/autoload.php";

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    "autoScriptToLang" => true,
    "autoLangToFont" => true,
    'useAdobeCJK' => true,
    // 防止中文水印乱码
    'watermark_font' => 'GB',
    'h2toc' => ['H3' => 0],
    'h2bookmarks' => ['H3' => 0],
]);

$mpdf->SetTitle('政策匹配报告');
$mpdf->SetAuthor('政策宝');
$mpdf->SetCreator('政策宝');
$mpdf->SetSubject('政策匹配报告');
$mpdf->SetKeywords('政策宝,政策匹配,报告');

// 导入的模板自带封面
$pagecount = $mpdf->setSourceFile('./template.pdf');
$tplId = $mpdf->importPage($pagecount);
$mpdf->useTemplate($tplId);

// 关闭封面页码
$mpdf->AddPage('','','','','off');

// 设置页眉和页脚
$mpdf->SetHeader('政策宝 (www.policybao.com)||');
$mpdf->SetFooter('|{PAGENO} / {nb}|');

// 目录
$mpdf->TOCpagebreakByArray([
    'links' => true,
    'paging' => true
]);

// 水印
$mpdf->SetWatermarkText('河南正文网络科技有限公司', 0.1);
$mpdf->showWatermarkText = true;

// 写入详细内容
for ($i = 0; $i < 10; $i++) {
    // $mpdf->AddPage();
    $mpdf->WriteHTML('
    <H3>抗疫扶持专项-失业保险稳岗' . str_repeat('补贴', mt_rand(1, 5)) . '</H3>
    <p> 
    <table border="1" cellspacing="0" cellpadding="10">
    <tbody>
    <tr>
    <th width="20%">项目名称</th>
    <td>抗疫扶持专项-失业保险稳岗补贴</td>
    </tr>
    
    <tr>
    <th>资助额度</th>
    <td>0</td>
    </tr>

    <tr>
    <th>企业类别及条件</th>
    <td>（1）按照疫情防控工作要求，符合亏损、利润下降、暂时性困难企
    业暂不受理，具体申报开始时间另行网上通知。<br>
    （2）受疫情影响的重点行业企业：申报企业向属地县（市、区）人</td>
    </tr>

    <tr>
    <th><b>支持力度</th>
    <td>待定</td>
    </tr>

    <tr>
    <th>申报时间</th>
    <td>2021年1月11日</td>
    </tr>

    <tr>
    <th>申报材料</th>
    <td>申报指南待发布</td>
    </tr>

    <tr>
    <th>解读分析</th>
    <td>1、失业保险稳岗补贴与失业保险应急稳岗返还补贴不能同时享受。
    2、联系电话：68064000
    </td>
    </tr>

    </tbody></table>
    </p>');
}

$mpdf->Output();
