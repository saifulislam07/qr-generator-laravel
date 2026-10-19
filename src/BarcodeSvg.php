<?php

namespace Saifulislam07\QrGenerator;

use Picqer\Barcode\Types\TypeCodabar;
use Picqer\Barcode\Types\TypeCode128;
use Picqer\Barcode\Types\TypeCode39;
use Picqer\Barcode\Types\TypeEan13;
use Picqer\Barcode\Types\TypeEan8;
use Picqer\Barcode\Types\TypeITF14;
use Picqer\Barcode\Types\TypeUpcA;

/**
 * Renders 1D barcodes as SVG with a quiet zone, optional background and the
 * human-readable text underneath -- the pieces picqer's own SVG renderer leaves out.
 */
class BarcodeSvg {
    /** Supported formats: form value => label, what it accepts, picqer type. */
    public const FORMATS = [
        'code128' => ['label' => 'Code 128', 'hint' => 'Any letters, digits and symbols (ASCII)', 'type' => TypeCode128::class],
        'code39'  => ['label' => 'Code 39', 'hint' => 'A-Z, 0-9 and - . space $ / + %', 'type' => TypeCode39::class],
        'ean13'   => ['label' => 'EAN-13', 'hint' => '12 or 13 digits', 'type' => TypeEan13::class],
        'ean8'    => ['label' => 'EAN-8', 'hint' => '7 or 8 digits', 'type' => TypeEan8::class],
        'upca'    => ['label' => 'UPC-A', 'hint' => '11 or 12 digits', 'type' => TypeUpcA::class],
        'itf14'   => ['label' => 'ITF-14', 'hint' => '13 or 14 digits', 'type' => TypeITF14::class],
        'codabar' => ['label' => 'Codabar', 'hint' => '0-9 and - $ : / . +', 'type' => TypeCodabar::class],
    ];

    /** Width of the narrowest bar, in SVG units. */
    protected const MODULE = 4;

    /** Height of the bars, in SVG units. */
    protected const BAR_HEIGHT = 160;

    /** Blank space left and right of the bars, in modules. Scanners need it to find the code. */
    protected const QUIET_ZONE = 10;

    /** Blank space above the bars and below the content, in SVG units. */
    protected const PADDING = 20;

    protected const FONT_SIZE = 28;

    /**
     * @throws \Picqer\Barcode\Exceptions\BarcodeException when the data does not fit the format
     */
    public static function render(string $data, string $format, string $color, ?string $bgColor, bool $showText = true): string {
        $type = self::FORMATS[$format]['type'];
        $barcode = (new $type())->getBarcode($data);

        $width = ($barcode->getWidth() + 2 * self::QUIET_ZONE) * self::MODULE;
        $textHeight = $showText ? 10 + self::FONT_SIZE : 0;
        $height = self::PADDING + self::BAR_HEIGHT + $textHeight + self::PADDING;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">' . "\n";

        if ($bgColor !== null) {
            $svg .= '<rect width="100%" height="100%" fill="' . $bgColor . '"/>' . "\n";
        }

        $svg .= '<g fill="' . $color . '">' . "\n";

        $x = self::QUIET_ZONE * self::MODULE;
        $scaleY = self::BAR_HEIGHT / $barcode->getHeight();

        foreach ($barcode->getBars() as $bar) {
            $barWidth = $bar->getWidth() * self::MODULE;

            if ($bar->isBar() && $barWidth > 0) {
                $y = self::PADDING + $bar->getPositionVertical() * $scaleY;
                $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . $barWidth . '" height="' . ($bar->getHeight() * $scaleY) . '"/>' . "\n";
            }

            $x += $barWidth;
        }

        $svg .= '</g>' . "\n";

        if ($showText) {
            $svg .= '<text x="' . ($width / 2) . '" y="' . (self::PADDING + self::BAR_HEIGHT + $textHeight - 6) . '"'
                . ' font-family="Consolas, Menlo, monospace" font-size="' . self::FONT_SIZE . '" text-anchor="middle"'
                . ' letter-spacing="2" fill="' . $color . '">' . htmlspecialchars($barcode->getBarcode()) . '</text>' . "\n";
        }

        return $svg . '</svg>' . "\n";
    }
}
