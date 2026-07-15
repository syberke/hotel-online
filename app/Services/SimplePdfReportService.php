<?php

namespace App\Services;

use Illuminate\Http\Response;

class SimplePdfReportService
{
    private const PAGE_WIDTH = 842;
    private const PAGE_HEIGHT = 595;
    private const MARGIN = 36;

    public function download(
        string $title,
        string $subtitle,
        array $summary,
        array $columns,
        array $rows,
        string $filename
    ): Response {
        $pages = $this->buildPages($title, $subtitle, $summary, $columns, $rows);
        $pdf = $this->compilePdf($pages);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($pdf),
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    private function buildPages(string $title, string $subtitle, array $summary, array $columns, array $rows): array
    {
        $pages = [];
        $pageNumber = 1;
        $content = $this->pageHeader($title, $subtitle, $pageNumber);
        $y = 500;

        if ($summary !== []) {
            [$summaryContent, $y] = $this->summaryBlock($summary, $y);
            $content .= $summaryContent;
            $y -= 18;
        }

        $columnWidths = $this->resolveColumnWidths($columns);
        $content .= $this->tableHeader($columns, $columnWidths, $y);
        $y -= 22;

        foreach ($rows as $row) {
            if ($y < 52) {
                $content .= $this->pageFooter($pageNumber);
                $pages[] = $content;
                $pageNumber++;
                $content = $this->pageHeader($title, $subtitle, $pageNumber);
                $y = 500;
                $content .= $this->tableHeader($columns, $columnWidths, $y);
                $y -= 22;
            }

            $content .= $this->tableRow($columns, $columnWidths, $row, $y, $y % 32 < 16);
            $y -= 18;
        }

        if ($rows === []) {
            $content .= $this->text('No records available for this report period.', self::MARGIN + 8, $y - 8, 9, false, [0.45, 0.45, 0.45]);
        }

        $content .= $this->pageFooter($pageNumber);
        $pages[] = $content;

        return $pages;
    }

    private function pageHeader(string $title, string $subtitle, int $pageNumber): string
    {
        $content = '';
        $content .= $this->fillRect(0, self::PAGE_HEIGHT - 72, self::PAGE_WIDTH, 72, [0.07, 0.07, 0.07]);
        $content .= $this->text('OASIS HOTEL & RESORT', self::MARGIN, 558, 9, true, [0.84, 0.60, 0.18]);
        $content .= $this->text($title, self::MARGIN, 536, 18, true, [1, 1, 1]);
        $content .= $this->text($subtitle, self::MARGIN, 520, 8, false, [0.76, 0.76, 0.76]);
        $content .= $this->text('Generated ' . now()->format('d M Y H:i') . ' | Page ' . $pageNumber, 650, 558, 8, false, [0.76, 0.76, 0.76]);
        $content .= $this->strokeLine(self::MARGIN, 512, self::PAGE_WIDTH - self::MARGIN, 512, [0.80, 0.80, 0.80], 0.5);

        return $content;
    }

    private function summaryBlock(array $summary, float $y): array
    {
        $content = '';
        $items = array_slice($summary, 0, 8, true);
        $perRow = 4;
        $boxGap = 8;
        $available = self::PAGE_WIDTH - (self::MARGIN * 2);
        $boxWidth = ($available - ($boxGap * ($perRow - 1))) / $perRow;
        $boxHeight = 42;

        foreach (array_values($items) as $index => $item) {
            $row = intdiv($index, $perRow);
            $column = $index % $perRow;
            $x = self::MARGIN + ($column * ($boxWidth + $boxGap));
            $boxY = $y - ($row * ($boxHeight + $boxGap));

            $content .= $this->fillRect($x, $boxY - $boxHeight, $boxWidth, $boxHeight, [0.97, 0.97, 0.96]);
            $content .= $this->strokeRect($x, $boxY - $boxHeight, $boxWidth, $boxHeight, [0.86, 0.85, 0.82], 0.5);
            $content .= $this->text((string) $item['label'], $x + 8, $boxY - 13, 7, true, [0.42, 0.42, 0.40]);
            $content .= $this->text((string) $item['value'], $x + 8, $boxY - 29, 11, true, [0.08, 0.08, 0.08]);
        }

        $rows = (int) ceil(count($items) / $perRow);
        $newY = $y - ($rows * ($boxHeight + $boxGap));

        return [$content, $newY];
    }

    private function tableHeader(array $columns, array $columnWidths, float $y): string
    {
        $content = $this->fillRect(self::MARGIN, $y - 18, self::PAGE_WIDTH - (self::MARGIN * 2), 18, [0.10, 0.10, 0.10]);
        $x = self::MARGIN;

        foreach ($columns as $index => $column) {
            $content .= $this->text(
                strtoupper((string) $column['label']),
                $x + 5,
                $y - 12,
                7,
                true,
                [1, 1, 1],
                $columnWidths[$index] - 10
            );
            $x += $columnWidths[$index];
        }

        return $content;
    }

    private function tableRow(array $columns, array $columnWidths, array $row, float $y, bool $alternate): string
    {
        $content = '';
        if ($alternate) {
            $content .= $this->fillRect(self::MARGIN, $y - 16, self::PAGE_WIDTH - (self::MARGIN * 2), 18, [0.985, 0.985, 0.98]);
        }

        $x = self::MARGIN;
        foreach ($columns as $index => $column) {
            $value = $row[$column['key']] ?? '';
            $content .= $this->text(
                (string) $value,
                $x + 5,
                $y - 11,
                7.5,
                (bool) ($column['bold'] ?? false),
                [0.18, 0.18, 0.18],
                $columnWidths[$index] - 10
            );
            $x += $columnWidths[$index];
        }

        $content .= $this->strokeLine(self::MARGIN, $y - 16, self::PAGE_WIDTH - self::MARGIN, $y - 16, [0.90, 0.90, 0.88], 0.35);

        return $content;
    }

    private function pageFooter(int $pageNumber): string
    {
        $content = $this->strokeLine(self::MARGIN, 30, self::PAGE_WIDTH - self::MARGIN, 30, [0.80, 0.80, 0.80], 0.4);
        $content .= $this->text('CONFIDENTIAL MANAGEMENT REPORT | LIVE DATABASE SOURCE', self::MARGIN, 18, 6.5, true, [0.45, 0.45, 0.45]);
        $content .= $this->text('PAGE ' . $pageNumber, self::PAGE_WIDTH - 78, 18, 6.5, true, [0.45, 0.45, 0.45]);

        return $content;
    }

    private function resolveColumnWidths(array $columns): array
    {
        $available = self::PAGE_WIDTH - (self::MARGIN * 2);
        $weights = array_map(static fn (array $column) => (float) ($column['weight'] ?? 1), $columns);
        $totalWeight = array_sum($weights) ?: 1;

        return array_map(static fn (float $weight) => $available * ($weight / $totalWeight), $weights);
    }

    private function text(
        string $text,
        float $x,
        float $y,
        float $size,
        bool $bold = false,
        array $rgb = [0, 0, 0],
        ?float $maxWidth = null
    ): string {
        $safeText = $this->normalizeText($text);

        if ($maxWidth !== null) {
            $estimatedCharacterWidth = max(3.2, $size * 0.52);
            $maxCharacters = max(1, (int) floor($maxWidth / $estimatedCharacterWidth));
            if (strlen($safeText) > $maxCharacters) {
                $safeText = substr($safeText, 0, max(1, $maxCharacters - 3)) . '...';
            }
        }

        $font = $bold ? 'F2' : 'F1';
        [$r, $g, $b] = $rgb;

        return sprintf(
            "BT /%s %.2F Tf %.3F %.3F %.3F rg %.2F %.2F Td (%s) Tj ET\n",
            $font,
            $size,
            $r,
            $g,
            $b,
            $x,
            $y,
            $safeText
        );
    }

    private function fillRect(float $x, float $y, float $width, float $height, array $rgb): string
    {
        [$r, $g, $b] = $rgb;
        return sprintf("q %.3F %.3F %.3F rg %.2F %.2F %.2F %.2F re f Q\n", $r, $g, $b, $x, $y, $width, $height);
    }

    private function strokeRect(float $x, float $y, float $width, float $height, array $rgb, float $lineWidth): string
    {
        [$r, $g, $b] = $rgb;
        return sprintf("q %.3F %.3F %.3F RG %.2F w %.2F %.2F %.2F %.2F re S Q\n", $r, $g, $b, $lineWidth, $x, $y, $width, $height);
    }

    private function strokeLine(float $x1, float $y1, float $x2, float $y2, array $rgb, float $lineWidth): string
    {
        [$r, $g, $b] = $rgb;
        return sprintf("q %.3F %.3F %.3F RG %.2F w %.2F %.2F m %.2F %.2F l S Q\n", $r, $g, $b, $lineWidth, $x1, $y1, $x2, $y2);
    }

    private function normalizeText(string $text): string
    {
        $converted = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        $converted = $converted === false ? $text : $converted;
        $converted = preg_replace('/[\r\n\t]+/', ' ', $converted) ?? $converted;

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], trim($converted));
    }

    private function compilePdf(array $pageStreams): string
    {
        $objects = [];
        $pageObjectNumbers = [];

        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
        $objects[4] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

        foreach ($pageStreams as $index => $stream) {
            $pageObjectNumber = 5 + ($index * 2);
            $contentObjectNumber = $pageObjectNumber + 1;
            $pageObjectNumbers[] = $pageObjectNumber;

            $objects[$pageObjectNumber] = sprintf(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %d %d] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents %d 0 R >>',
                self::PAGE_WIDTH,
                self::PAGE_HEIGHT,
                $contentObjectNumber
            );

            $objects[$contentObjectNumber] = "<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "endstream";
        }

        $kids = implode(' ', array_map(static fn (int $number) => $number . ' 0 R', $pageObjectNumbers));
        $objects[2] = '<< /Type /Pages /Kids [' . $kids . '] /Count ' . count($pageObjectNumbers) . ' >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0 => 0];
        $maxObjectNumber = max(array_keys($objects));

        for ($objectNumber = 1; $objectNumber <= $maxObjectNumber; $objectNumber++) {
            if (!isset($objects[$objectNumber])) {
                continue;
            }

            $offsets[$objectNumber] = strlen($pdf);
            $pdf .= $objectNumber . " 0 obj\n" . $objects[$objectNumber] . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . ($maxObjectNumber + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($objectNumber = 1; $objectNumber <= $maxObjectNumber; $objectNumber++) {
            $offset = $offsets[$objectNumber] ?? 0;
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= "trailer\n<< /Size " . ($maxObjectNumber + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xrefOffset . "\n%%EOF";

        return $pdf;
    }
}
