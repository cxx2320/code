<?php

require './vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * 数据导出
 */
class ExportList
{
    /**
     * 对应的表头
     *
     * @var array
     */
    protected $header = [];

    /**
     * 要导出的数据
     *
     * @var array
     */
    protected $exportData = [];

    /**
     * @param array $header [
     *  '表头字段名称' => '对应数据字段',
     *  '姓名' => 'name'
     * ]
     * @param array $exportData
     */
    public function __construct(array $header, array $exportData)
    {
        $this->header = $header;
        $this->exportData = $exportData;
    }

    /**
     * 导出
     *
     * @param string $name 文件名(不需要文件后缀)
     * @return \think\response\File
     */
    public function export(string $name = '')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->setHeader($sheet);
        $this->writeData($sheet);
        $writer = new Xlsx($spreadsheet);
        $name = $name ?: date("YmdHis");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With, responseType');
        $writer->save('php://output');
        exit();
    }

    private function setHeader($sheet)
    {
        $a = range('A', 'Z');
        $i = 0;
        foreach ($this->header as $head => $field) {
            $sheet->setCellValue($a[$i++] . '1', $head);
        }
    }

    private function writeData($sheet)
    {
        $line = 2;
        $a = range('A', 'Z');
        $i = 0;
        foreach ($this->header as $field) {
            foreach ($this->exportData as $item) {
                $sheet->setCellValue($a[$i] . $line++, $item[$field] ?? '');
            }
            $line = 2;
            $i++;
        }
    }
}

$a = new ExportList([
    '姓名' => 'name',
    '年龄' => 'age'
], [
    ['name' => 'cxx', 'age' => 18],
    ['name' => '2131', 'age' => 18],
    ['name' => '4535', 'age' => 18],
    ['name' => 'asda', 'age' => 18],
    ['name' => 'zxcz', 'age' => 18],
    ['name' => 'fdgd', 'age' => 18],
    ['name' => 'hdgh', 'age' => 18],
]);

$a->export();