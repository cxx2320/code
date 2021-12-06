<?php

declare(strict_types=1);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
     * @return void
     */
    public function export(string $name = '')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->setHeader($sheet);
        $this->writeData($sheet);
        $writer = new Xlsx($spreadsheet);
        $name = $name ?: date("YmdHis");

        ob_start();
        $writer->save('php://output');
        $data = ob_get_contents();
        ob_end_clean();

        return response($data, 200, [], 'file')
            ->isContent(true)
            ->mimeType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header([
                'Access-Control-Expose-Headers' => 'Content-Disposition',
                'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With, responseType'
            ])
            ->name('File - ' . $name . '.xlsx');
    }

    /**
     * 写入表头
     *
     * @param Worksheet $sheet
     * @return void
     */
    private function setHeader($sheet)
    {
        $a = $this->wordInc(count($this->header) > 26 ? 2 : 1);
        $i = 0;
        foreach ($this->header as $head => $field) {
            $sheet->setCellValue($a[$i++] . '1', $head);
        }
    }

    /**
     * 写入数据
     *
     * @param Worksheet $sheet
     * @return void
     */
    private function writeData($sheet)
    {
        $line = 2;
        $a = $this->wordInc(count($this->header) > 26 ? 2 : 1);
        $i = 0;
        foreach ($this->header as $field) {
            foreach ($this->exportData as $item) {
                $sheet->setCellValueExplicit($a[$i] . $line++, $item[$field] ?? '', DataType::TYPE_STRING);
            }
            $line = 2;
            $i++;
        }
    }

    /**
     * 单词自增序列 (生成的条目数为 $level * 26 )
     *
     * @param integer $level
     * @return array
     */
    public function wordInc(int $level = 1): array
    {
        $words = range('A', 'Z');
        if ($level <= 1) {
            return $words;
        }
        $level--;
        for ($j = 0; $j < $level; $j++) {
            for ($i = 0; $i < 26; $i++) {
                $words[] = $words[$j] . $words[$i];
            }
        }
        return $words;
    }
}
