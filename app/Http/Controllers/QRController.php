<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Picqer\Barcode\Exceptions\BarcodeException;
use Saifulislam07\QrGenerator\BarcodeSvg;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller {
    /**
     * Nominal pixel size of the generated QR code. The output is SVG, so this
     * does not enlarge the file meaningfully -- it sets the natural size other
     * tools rasterize it at, which is what keeps the image from looking blocky
     * when it is placed in a document or printed.
     */
    protected const SIZE = 1000;

    /** Quiet zone around the code, in modules. The QR spec needs one to scan reliably. */
    protected const MARGIN = 1;

    /** Error correction level: M recovers ~15% damage, vs ~7% for the L default. */
    protected const ERROR_CORRECTION = 'M';

    public function index() {
        return view('qr.index');
    }

    public function generate(Request $request) {
        $request->validate([
            'data'     => 'required|string',
            'color'    => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'bg_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'transparent' => ['nullable', 'boolean'],
            'custom_eyes' => ['nullable', 'boolean'],
            'eye_color'       => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'eye_inner_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'type'           => ['nullable', Rule::in(['qr', 'barcode'])],
            'barcode_format' => ['nullable', Rule::in(array_keys(BarcodeSvg::FORMATS))],
            'show_text'      => ['nullable', 'boolean'],
        ]);

        // Browsers submit textarea line breaks as \r\n; normalize to \n so
        // multi-line content encodes consistently.
        $data = str_replace(["\r\n", "\r"], "\n", $request->data);
        $color = $request->input('color', '#000000');
        $bgColor = $request->input('bg_color', '#ffffff');
        $transparent = $request->boolean('transparent');

        if ($request->input('type') === 'barcode') {
            return $this->generateBarcode($request, $data, $color, $transparent ? null : $bgColor);
        }


        $customEyes = $request->boolean('custom_eyes');
        $eyeColor = $request->input('eye_color', $color);
        $eyeInnerColor = $request->input('eye_inner_color', $color);

        // Helper function to convert hex to RGB
        $hexToRgb = function ($hex) {
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            } else {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            }
            return [$r, $g, $b];
        };

        [$r, $g, $b] = $hexToRgb($color);
        [$bg_r, $bg_g, $bg_b] = $hexToRgb($bgColor);

        // Generate QR code with colors
        $qr = QrCode::size(self::SIZE)
            ->margin(self::MARGIN)
            ->errorCorrection(self::ERROR_CORRECTION)
            ->color($r, $g, $b)
            ->backgroundColor($bg_r, $bg_g, $bg_b, $transparent ? 0 : null);

        // Optionally recolor the three corner "eyes" (outer frame + inner square)
        if ($customEyes) {
            [$er, $eg, $eb] = $hexToRgb($eyeColor);
            [$ir, $ig, $ib] = $hexToRgb($eyeInnerColor);

            foreach ([0, 1, 2] as $eye) {
                $qr->eyeColor($eye, $ir, $ig, $ib, $er, $eg, $eb);
            }
        }

        $qrCodeSvg = $qr->generate($data);

        return view('qr.index', [
            'type'     => 'qr',
            'qrCode'   => $qrCodeSvg,
            'fileName' => $this->storeSvg('qr', $qrCodeSvg),
            'data'     => $data,
            'color'    => $color,
            'bg_color' => $bgColor,
            'transparent' => $transparent,
            'custom_eyes' => $customEyes,
            'eye_color' => $eyeColor,
            'eye_inner_color' => $eyeInnerColor,
        ]);
    }

    protected function generateBarcode(Request $request, string $data, string $color, ?string $bgColor) {
        $format = $request->input('barcode_format', 'code128');
        $showText = $request->boolean('show_text');

        // Barcodes are a single row of bars; there is no way to encode a line break.
        if (str_contains($data, "\n")) {
            throw ValidationException::withMessages([
                'data' => 'Barcodes can only hold a single line. Remove the line breaks or switch to QR Code.',
            ]);
        }

        try {
            $svg = BarcodeSvg::render($data, $format, $color, $bgColor, $showText);
        } catch (BarcodeException $e) {
            $spec = BarcodeSvg::FORMATS[$format];

            throw ValidationException::withMessages([
                'data' => "This content can't be encoded as {$spec['label']}. It accepts: {$spec['hint']}.",
            ]);
        }

        return view('qr.index', [
            'type'           => 'barcode',
            'barcode_format' => $format,
            'show_text'      => $showText,
            'qrCode'         => $svg,
            'fileName'       => $this->storeSvg('barcode', $svg),
            'data'           => $data,
            'color'          => $color,
            'bg_color'       => $request->input('bg_color', '#ffffff'),
            'transparent'    => $bgColor === null,
        ]);
    }

    /**
     * Save the SVG under public/qrcodes and return its file name.
     */
    protected function storeSvg(string $prefix, string $svg): string {
        $fileName = $prefix . '_' . Str::random(6) . '.svg';

        if (! file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }

        file_put_contents(public_path('qrcodes/' . $fileName), $svg);

        return $fileName;
    }

    public function download($fileName) {
        $filePath = public_path('qrcodes/' . $fileName);

        if (! file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        $downloadName = str_starts_with($fileName, 'barcode_') ? 'barcode.svg' : 'qrcode.svg';

        return response()->download($filePath, $downloadName, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }
}
