<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        ]);

        $data = $request->data;
        $color = $request->input('color', '#000000');
        $bgColor = $request->input('bg_color', '#ffffff');
        $transparent = $request->boolean('transparent');
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

        // Generate unique filename
        $fileName = 'qr_' . Str::random(6) . '.svg';
        $filePath = public_path('qrcodes/' . $fileName);

        // Make sure directory exists
        if (! file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }

        // Save the QR code SVG file
        file_put_contents($filePath, $qrCodeSvg);

        return view('qr.index', [
            'qrCode'   => $qrCodeSvg,
            'fileName' => $fileName,
            'data'     => $data,
            'color'    => $color,
            'bg_color' => $bgColor,
            'transparent' => $transparent,
            'custom_eyes' => $customEyes,
            'eye_color' => $eyeColor,
            'eye_inner_color' => $eyeInnerColor,
        ]);
    }

    public function download($fileName) {
        $filePath = public_path('qrcodes/' . $fileName);

        if (! file_exists($filePath)) {
            abort(404, 'QR Code not found.');
        }

        return response()->download($filePath, 'qrcode.svg', [
            'Content-Type' => 'image/svg+xml',
        ]);
    }
}
