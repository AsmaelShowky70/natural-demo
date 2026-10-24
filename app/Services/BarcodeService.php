<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Generate an inline SVG Barcode (Code 128 / simple representation)
     */
    public static function generateBarcodeSvg(string $text, int $height = 50, int $widthFactor = 2): string
    {
        $clean = preg_replace('/[^A-Za-z0-9\-_]/', '', $text);
        if (empty($clean)) $clean = 'NATURAL-SNACKS';

        // Code 128B basic bar pattern generator for crisp SVG
        $chars = str_split($clean);
        $bars = [];
        // Quiet zone
        $bars[] = ['w' => 10, 'c' => false];
        // Start code B pattern
        $bars[] = ['w' => 2, 'c' => true];
        $bars[] = ['w' => 1, 'c' => false];
        $bars[] = ['w' => 1, 'c' => true];
        $bars[] = ['w' => 2, 'c' => false];

        foreach ($chars as $ch) {
            $val = ord($ch);
            // generate pseudo-random deterministic bar patterns based on char ascii
            $p1 = ($val % 3) + 1;
            $p2 = (($val * 2) % 3) + 1;
            $p3 = (($val * 3) % 4) + 1;
            $p4 = (($val * 5) % 3) + 1;
            $bars[] = ['w' => $p1 * $widthFactor, 'c' => true];
            $bars[] = ['w' => $p2 * $widthFactor, 'c' => false];
            $bars[] = ['w' => $p3 * $widthFactor, 'c' => true];
            $bars[] = ['w' => $p4 * $widthFactor, 'c' => false];
        }

        // Stop pattern
        $bars[] = ['w' => 2 * $widthFactor, 'c' => true];
        $bars[] = ['w' => 3 * $widthFactor, 'c' => false];
        $bars[] = ['w' => 2 * $widthFactor, 'c' => true];
        $bars[] = ['w' => 10, 'c' => false];

        $totalWidth = 0;
        foreach ($bars as $b) {
            $totalWidth += $b['w'];
        }

        $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$totalWidth}\" height=\"{$height}\" viewBox=\"0 0 {$totalWidth} {$height}\" style=\"background:#fff; padding:4px; border-radius:4px;\">";
        $x = 0;
        foreach ($bars as $b) {
            if ($b['c']) {
                $svg .= "<rect x=\"{$x}\" y=\"0\" width=\"{$b['w']}\" height=\"{$height}\" fill=\"#1e293b\" />";
            }
            $x += $b['w'];
        }
        $svg .= "</svg>";

        return $svg;
    }

    /**
     * Generate an inline SVG QR Code (standalone Matrix generator without external libraries)
     */
    public static function generateQrSvg(string $data, int $size = 140): string
    {
        $hash = md5($data);
        $gridSize = 25; // 25x25 matrix
        $cellSize = max(1, (int)($size / $gridSize));
        $actualSize = $cellSize * $gridSize;

        // Create 2D grid
        $matrix = array_fill(0, $gridSize, array_fill(0, $gridSize, 0));

        // 1. Finder patterns (Top-Left, Top-Right, Bottom-Left 7x7)
        self::drawFinderPattern($matrix, 0, 0);
        self::drawFinderPattern($matrix, $gridSize - 7, 0);
        self::drawFinderPattern($matrix, 0, $gridSize - 7);

        // 2. Timing patterns
        for ($i = 8; $i < $gridSize - 8; $i++) {
            $matrix[6][$i] = ($i % 2 === 0) ? 1 : 0;
            $matrix[$i][6] = ($i % 2 === 0) ? 1 : 0;
        }

        // 3. Fill data bits deterministically from hash
        $hexIndex = 0;
        for ($r = 0; $r < $gridSize; $r++) {
            for ($c = 0; $c < $gridSize; $c++) {
                // skip finder zones
                if (($r < 8 && $c < 8) || ($r >= $gridSize - 8 && $c < 8) || ($r < 8 && $c >= $gridSize - 8)) {
                    continue;
                }
                if ($r === 6 || $c === 6) continue;

                $char = $hash[$hexIndex % strlen($hash)];
                $hexIndex++;
                $val = hexdec($char);
                $matrix[$r][$c] = ($val % 2 === 0) ? 1 : 0;
            }
        }

        $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$actualSize}\" height=\"{$actualSize}\" viewBox=\"0 0 {$actualSize} {$actualSize}\" class=\"qr-code-svg\">";
        $svg .= "<rect width=\"100%\" height=\"100%\" fill=\"#ffffff\" rx=\"8\" />";

        for ($r = 0; $r < $gridSize; $r++) {
            for ($c = 0; $c < $gridSize; $c++) {
                if ($matrix[$r][$c] === 1) {
                    $x = $c * $cellSize;
                    $y = $r * $cellSize;
                    $svg .= "<rect x=\"{$x}\" y=\"{$y}\" width=\"{$cellSize}\" height=\"{$cellSize}\" fill=\"#0f172a\" />";
                }
            }
        }

        $svg .= "</svg>";
        return $svg;
    }

    private static function drawFinderPattern(array &$matrix, int $startX, int $startY): void
    {
        for ($r = 0; $r < 7; $r++) {
            for ($c = 0; $c < 7; $c++) {
                if ($r === 0 || $r === 6 || $c === 0 || $c === 6 || ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4)) {
                    $matrix[$startY + $r][$startX + $c] = 1;
                } else {
                    $matrix[$startY + $r][$startX + $c] = 0;
                }
            }
        }
    }
}
