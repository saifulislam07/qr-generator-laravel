<!--
=============================================
  QR Code Generator
  Developed by: Md. Saiful Islam
  GitHub: https://github.com/saifulislam07
=============================================
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <meta name="description" content="Generate beautiful, custom-colored QR codes instantly. Free online QR code generator with color customization.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #0a0a1a;
            --bg-secondary: #12122a;
            --bg-card: rgba(255, 255, 255, 0.04);
            --bg-card-hover: rgba(255, 255, 255, 0.07);
            --border-color: rgba(255, 255, 255, 0.08);
            --border-glow: rgba(99, 102, 241, 0.3);
            --text-primary: #f1f1f7;
            --text-secondary: #9394a5;
            --text-muted: #5e5f73;
            --accent-primary: #6366f1;
            --accent-secondary: #818cf8;
            --accent-glow: rgba(99, 102, 241, 0.15);
            --success: #10b981;
            --success-glow: rgba(16, 185, 129, 0.15);
            --gradient-1: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a78bfa 100%);
            --gradient-2: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --shadow-soft: 0 4px 24px rgba(0, 0, 0, 0.3);
            --shadow-glow: 0 0 40px rgba(99, 102, 241, 0.08);
            --radius-sm: 10px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(ellipse 600px 600px at 20% 30%, rgba(99, 102, 241, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse 500px 500px at 80% 70%, rgba(139, 92, 246, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse 400px 400px at 50% 50%, rgba(16, 185, 129, 0.04) 0%, transparent 60%);
            animation: bgFloat 20s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(1deg); }
            66% { transform: translate(-20px, 20px) rotate(-1deg); }
        }

        .page-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
            padding: 20px;
        }

        /* Header */
        .page-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 56px;
            height: 56px;
            background: var(--gradient-1);
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.25);
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .logo-icon svg {
            width: 28px;
            height: 28px;
            fill: white;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #f1f1f7 0%, #9394a5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 6px;
        }

        .page-header p {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Card */
        .generator-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-xl);
            padding: 32px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: var(--shadow-soft), var(--shadow-glow);
            transition: border-color 0.4s ease;
        }

        .generator-card:hover {
            border-color: var(--border-glow);
        }

        /* Form elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 15px;
            font-weight: 400;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-input:focus {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px var(--accent-glow);
            background: rgba(255, 255, 255, 0.06);
        }

        /* Color picker row */
        .color-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .color-group {
            position: relative;
        }

        .color-picker-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .color-picker-wrapper:hover {
            border-color: rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.06);
        }

        .color-input {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background: none;
            padding: 0;
            -webkit-appearance: none;
            appearance: none;
        }

        .color-input::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        .color-input::-webkit-color-swatch {
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        .color-input::-moz-color-swatch {
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        .color-hex {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            font-family: 'Inter', monospace;
            text-transform: uppercase;
        }

        /* Submit button */
        .btn-generate {
            width: 100%;
            padding: 16px 24px;
            background: var(--gradient-1);
            border: none;
            border-radius: var(--radius-sm);
            color: white;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }

        .btn-generate::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.5s ease;
        }

        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.35);
        }

        .btn-generate:hover::before {
            left: 100%;
        }

        .btn-generate:active {
            transform: translateY(0);
        }

        /* Button row */
        .btn-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
        }

        /* Reset button */
        .btn-reset {
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-reset svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: transform 0.4s ease;
        }

        .btn-reset:hover {
            background: rgba(239, 68, 68, 0.08);
            border-color: rgba(239, 68, 68, 0.25);
            color: #f87171;
            transform: translateY(-2px);
        }

        .btn-reset:hover svg {
            transform: rotate(-180deg);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        /* Divider */
        .result-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
            margin: 28px 0;
        }

        /* QR Result */
        .qr-result {
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .qr-result-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .qr-preview {
            display: inline-flex;
            padding: 24px;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 20px 60px rgba(0, 0, 0, 0.4);
            margin-bottom: 24px;
            transition: transform 0.3s ease;
        }

        .qr-preview:hover {
            transform: scale(1.03);
        }

        .qr-preview svg {
            width: 220px !important;
            height: 220px !important;
            display: block;
        }

        /* Download button */
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: var(--gradient-2);
            border: none;
            border-radius: var(--radius-sm);
            color: white;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
        }

        .btn-download svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(16, 185, 129, 0.35);
            color: white;
            text-decoration: none;
        }

        .btn-download:active {
            transform: translateY(0);
        }

        /* Download buttons */
        .download-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }

        .btn-download.is-secondary {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: var(--text-secondary);
        }

        .btn-download.is-secondary:hover {
            border-color: rgba(255, 255, 255, 0.35);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: var(--text-primary);
        }

        .btn-download[disabled] {
            opacity: 0.6;
            cursor: progress;
            transform: none;
        }

        /* Validation errors */
        .error-list {
            list-style: none;
            margin-bottom: 20px;
            padding: 14px 18px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: var(--radius-sm);
        }

        .error-list li {
            font-size: 13px;
            color: #f87171;
            padding: 2px 0;
        }

        /* Footer */
        .page-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Responsive */
        @media (max-width: 576px) {
            .page-wrapper {
                padding: 16px;
            }

            .generator-card {
                padding: 24px 20px;
                border-radius: var(--radius-lg);
            }

            .page-header h1 {
                font-size: 24px;
            }

            .color-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .qr-preview {
                padding: 20px;
            }

            .qr-preview svg {
                width: 180px !important;
                height: 180px !important;
            }

            .logo-icon {
                width: 48px;
                height: 48px;
            }
        }

        @media (max-width: 380px) {
            .generator-card {
                padding: 20px 16px;
            }

            .qr-preview svg {
                width: 150px !important;
                height: 150px !important;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <header class="page-header">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm11-2h2v2h-2v-2zm-4 0h2v2h-2v-2zm8 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                </svg>
            </div>
            <h1>QR Code Generator</h1>
            <p>Create custom colored QR codes in seconds</p>
        </header>

        <!-- Generator Card -->
        <div class="generator-card">
            @if ($errors->any())
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('qr.generate') }}" id="qr-form">
                @csrf

                <div class="form-group">
                    <label for="data" class="form-label">Content</label>
                    <input
                        type="text"
                        name="data"
                        id="data"
                        class="form-input"
                        placeholder="Enter text, URL, or any data..."
                        required
                        value="{{ $data ?? old('data') }}"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">Colors</label>
                    <div class="color-row">
                        <div class="color-group">
                            <div class="color-picker-wrapper" onclick="this.querySelector('input').click()">
                                <input type="color" name="color" id="color" class="color-input" value="{{ $color ?? '#000000' }}" oninput="updateHex(this)">
                                <div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">Foreground</div>
                                    <span class="color-hex" id="color-hex">{{ $color ?? '#000000' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="color-group">
                            <div class="color-picker-wrapper" onclick="this.querySelector('input').click()">
                                <input type="color" name="bg_color" id="bg_color" class="color-input" value="{{ $bg_color ?? '#ffffff' }}" oninput="updateHex(this)">
                                <div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">Background</div>
                                    <span class="color-hex" id="bg-color-hex">{{ $bg_color ?? '#ffffff' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;margin-top:12px;font-size:13px;color:var(--text-muted);cursor:pointer;">
                        <input type="checkbox" name="transparent" id="transparent" value="1" {{ !empty($transparent) ? 'checked' : '' }}>
                        Transparent background
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;margin-top:12px;font-size:13px;color:var(--text-muted);cursor:pointer;">
                        <input type="checkbox" name="custom_eyes" id="custom_eyes" value="1" {{ !empty($custom_eyes) ? 'checked' : '' }} onchange="document.getElementById('eye-colors').style.display = this.checked ? '' : 'none'">
                        Custom corner (eye) colors
                    </label>
                    <div class="color-row" id="eye-colors" style="margin-top:12px;{{ !empty($custom_eyes) ? '' : 'display:none' }}">
                        <div class="color-group">
                            <div class="color-picker-wrapper" onclick="this.querySelector('input').click()">
                                <input type="color" name="eye_color" id="eye_color" class="color-input" value="{{ $eye_color ?? '#000000' }}" oninput="updateHex(this)">
                                <div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">Corner frame</div>
                                    <span class="color-hex">{{ $eye_color ?? '#000000' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="color-group">
                            <div class="color-picker-wrapper" onclick="this.querySelector('input').click()">
                                <input type="color" name="eye_inner_color" id="eye_inner_color" class="color-input" value="{{ $eye_inner_color ?? '#000000' }}" oninput="updateHex(this)">
                                <div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">Corner center</div>
                                    <span class="color-hex">{{ $eye_inner_color ?? '#000000' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-row">
                    <button type="submit" class="btn-generate" id="btn-generate">
                        ✦ Generate QR Code
                    </button>
                    <a href="{{ url('/') }}" class="btn-reset" title="Reset">
                        <svg viewBox="0 0 24 24">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>

            @isset($qrCode)
                <div class="result-divider"></div>

                <div class="qr-result">
                    <div class="qr-result-label">Your QR Code</div>
                    <div class="qr-preview" id="qr-preview" style="{{ !empty($transparent) ? 'background: repeating-conic-gradient(#d9d9e3 0% 25%, #ffffff 0% 50%) 50% / 20px 20px' : 'background: ' . ($bg_color ?? '#ffffff') }}">
                        {!! $qrCode !!}
                    </div>
                    <div class="download-actions">
                        <a href="{{ route('qr.download', $fileName) }}" class="btn-download">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download SVG
                        </a>
                        <button type="button" class="btn-download is-secondary" id="btn-download-png" onclick="downloadPng(this)">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download PNG
                        </button>
                    </div>
                </div>
            @endisset
        </div>

        <footer class="page-footer">
           Developed by <a style="color: var(--accent-secondary); text-decoration: none; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='#a78bfa'" onmouseout="this.style.color='var(--accent-secondary)'" href="https://github.com/saifulislam07" target="_blank">Md. Saiful Islam</a>
        </footer>
    </div>

    <script>
        function updateHex(input) {
            const wrapper = input.closest('.color-picker-wrapper');
            const hexLabel = wrapper.querySelector('.color-hex');
            hexLabel.textContent = input.value.toUpperCase();
        }

        /* Side of the exported PNG, in pixels. The QR is vector on the page, so
           this can be raised freely for print without touching the server. */
        const PNG_SIZE = 2000;

        /* Rasterise the inline SVG through a canvas. Doing it here keeps the PNG
           export working without the imagick extension the server would need. */
        function downloadPng(button) {
            const svg = document.querySelector('#qr-preview svg');
            if (!svg) return;

            const clone = svg.cloneNode(true);
            clone.setAttribute('width', PNG_SIZE);
            clone.setAttribute('height', PNG_SIZE);
            clone.setAttribute('xmlns', 'http://www.w3.org/2000/svg');

            // btoa() is latin1-only, so widen the markup to bytes before encoding.
            const source = new XMLSerializer().serializeToString(clone);
            const bytes = new TextEncoder().encode(source);
            const binary = Array.from(bytes, byte => String.fromCharCode(byte)).join('');

            button.disabled = true;

            const image = new Image();

            image.onload = function () {
                const canvas = document.createElement('canvas');
                canvas.width = PNG_SIZE;
                canvas.height = PNG_SIZE;
                canvas.getContext('2d').drawImage(image, 0, 0, PNG_SIZE, PNG_SIZE);

                canvas.toBlob(function (blob) {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'qrcode.png';
                    link.click();
                    URL.revokeObjectURL(url);
                    button.disabled = false;
                }, 'image/png');
            };

            image.onerror = function () {
                button.disabled = false;
                alert('Could not build the PNG. Please use the SVG download instead.');
            };

            image.src = 'data:image/svg+xml;base64,' + btoa(binary);
        }
    </script>
</body>

</html>