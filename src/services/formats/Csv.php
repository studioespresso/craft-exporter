<?php

namespace studioespresso\exporter\services\formats;

use Craft;
use craft\base\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use studioespresso\exporter\elements\ExportElement;

class Csv extends Component
{
    public function create(ExportElement $export, array $data, $fileName): string
    {
        $counts = array_map('count', $data);
        $key = array_flip($counts)[max($counts)];
        $largestRow = $data[$key];

        $keys = array_keys($largestRow);
        $template = array_fill_keys($keys, '');

        $exportData = array_map(function($item) use ($template) {
            return array_merge($template, $item);
        }, $data);

        $rows = array_map(function($row) {
            return array_values($row);
        }, $exportData);

        array_unshift($rows, array_keys($exportData[0]));

        try {
            ob_end_clean();
        } catch (\Throwable $e) {
            Craft::error($e->getMessage());
        }


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->setDelimiter(';');
        $writer->setEnclosure('"');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);

        $date = new \DateTime();
        $path = Craft::$app->getPath()->getTempPath() . "/{$fileName}.csv";
        $writer->save($path);
        return $path;
    }
}
