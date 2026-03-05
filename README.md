<p align="center">
  <img src="public/img/1.png" alt="QR Code Generator" width="600">
</p>

<h1 align="center">🔲 QR Code Generator for Laravel</h1>

<p align="center">
  A modern, stylish QR Code Generator package for Laravel — featuring custom color support, premium dark UI, instant preview, and SVG download.
</p>

<p align="center">
  <a href="https://packagist.org/packages/saifulislam07/qr-generator-laravel"><img src="https://img.shields.io/packagist/v/saifulislam07/qr-generator-laravel?style=for-the-badge&color=6366f1" alt="Packagist Version"></a>
  <img src="https://img.shields.io/badge/Laravel-10|11|12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

---

## ✨ Features

- 🎨 **Custom Colors** — Pick any foreground and background color for your QR code
- 📱 **Fully Responsive** — Beautiful dark-themed UI that works on all devices
- ⬇️ **SVG Download** — Download your QR code as a high-quality SVG file
- 🔄 **Reset Button** — Quickly clear the form and start fresh
- ⚡ **Instant Preview** — See your QR code immediately after generation
- 🌙 **Premium Dark UI** — Glassmorphism design with smooth animations
- 📦 **Easy Install** — Install via Composer in seconds

---

## 📸 Screenshots

|      Generator Form       |      Generated QR Code      |
| :-----------------------: | :-------------------------: |
| ![Form](public/img/1.png) | ![Result](public/img/2.png) |

---

## 📦 Installation (Composer Package)

Install the package via Composer:

```bash
composer require saifulislam07/qr-generator-laravel
```

The package will **auto-register** its service provider via Laravel's package discovery.

### Access the Generator

After installation, visit:

```
http://your-app.test/qr-generator
```

### Publish Views (Optional)

If you want to customize the UI, publish the views:

```bash
php artisan vendor:publish --tag=qr-generator-views
```

This will copy the views to `resources/views/vendor/qr-generator/`.

---

## 🛠️ Manual Setup (Clone & Run as Standalone App)

If you want to run this as a standalone Laravel application:

### Prerequisites

- **PHP** >= 8.2
- **Composer** (PHP dependency manager)
- **A local server** (e.g., Laragon, XAMPP, WAMP, or Laravel Valet)

### Step-by-Step

**1. Clone the repository**

```bash
git clone https://github.com/saifulislam07/qr-generator-laravel.git
```

**2. Navigate to the project folder**

```bash
cd qr-generator-laravel
```

**3. Install PHP dependencies**

```bash
composer install
```

**4. Create the environment file**

```bash
cp .env.example .env
```

**5. Generate the application key**

```bash
php artisan key:generate
```

**6. Run the development server**

```bash
php artisan serve
```

**7. Open in your browser**

```
http://localhost:8000
```

---

## 🛠️ Tech Stack

| Technology                  | Purpose                         |
| --------------------------- | ------------------------------- |
| **Laravel 10/11/12**        | Backend framework               |
| **SimpleSoftwareIO/QrCode** | QR code generation              |
| **HTML/CSS/JS**             | Frontend with custom dark theme |

---

## 📁 Package Structure

```
qr-generator-laravel/
├── src/
│   ├── Http/Controllers/
│   │   └── QRController.php            # QR generation & download logic
│   ├── resources/views/
│   │   └── index.blade.php             # Premium dark-themed UI
│   ├── routes/
│   │   └── web.php                     # Package routes (/qr-generator)
│   └── QrGeneratorServiceProvider.php  # Service provider
├── public/img/                         # Screenshots
├── composer.json                       # Package definition
└── README.md
```

---

## 📝 Usage

1. Install the package or clone the repo
2. Open the QR generator page in your browser
3. Enter any **text or URL** in the input field
4. Choose a **foreground color** (QR code color)
5. Choose a **background color**
6. Click **"✦ Generate QR Code"**
7. Click **"Download SVG"** to save the file
8. Use the **"Reset"** button to start over

---

## 👤 Author

**Md. Saiful Islam**

- GitHub: [@saifulislam07](https://github.com/saifulislam07)

---

## 📄 License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).
