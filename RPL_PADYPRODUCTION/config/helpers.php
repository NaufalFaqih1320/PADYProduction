<?php

/**
 * config/helpers.php — PADY Production
 * Berisi semua fungsi helper yang digunakan di seluruh aplikasi.
 *
 * Perubahan dari versi lama:
 *  - Tambah fungsi getFlashErrors() untuk tampil error form
 *  - Tambah fungsi getOldInput() untuk repopulate form
 *  - Perbaiki processReminders() agar tidak error jika tabel belum ada data
 */

// ─── Sanitasi ────────────────────────────────────────────────────────────────

function sanitize($conn, $data): string {
    return mysqli_real_escape_string($conn, trim((string)$data));
}

// ─── Flash Message ───────────────────────────────────────────────────────────

function setFlashMessage(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlashMessage(): string {
    if (empty($_SESSION['flash'])) return '';
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $colors = [
        'success' => 'background:#d4edda;color:#155724;border-color:#c3e6cb',
        'danger'  => 'background:#f8d7da;color:#721c24;border-color:#f5c6cb',
        'info'    => 'background:#d1ecf1;color:#0c5460;border-color:#bee5eb',
        'warning' => 'background:#fff3cd;color:#856404;border-color:#ffeeba',
    ];
    $style = $colors[$f['type']] ?? $colors['info'];
    return "<div style='{$style};padding:10px 16px;border:1px solid;border-radius:6px;margin:10px 0;'>{$f['message']}</div>";
}

function redirectWithMessage(string $url, string $type, string $message): void {
    setFlashMessage($type, $message);
    header("Location: $url");
    exit();
}

// ─── Form Error Helper ────────────────────────────────────────────────────────

/**
 * Tampilkan daftar error form (dari $_SESSION['xxx_errors'])
 * Contoh: echo getFlashErrors('booking_errors');
 */
function getFlashErrors(string $key): string {
    if (empty($_SESSION[$key])) return '';
    $errors = $_SESSION[$key];
    unset($_SESSION[$key]);
    $html = "<div style='background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:10px 16px;border-radius:6px;margin:10px 0;'><ul style='margin:0;padding-left:20px;'>";
    foreach ($errors as $e) {
        $html .= "<li>$e</li>";
    }
    return $html . "</ul></div>";
}

/**
 * Ambil nilai lama saat form di-redirect balik karena error.
 * Contoh: value="<?= getOldInput('booking_old', 'nama_acara'); ?>"
 */
function getOldInput(string $key, string $field, string $default = ''): string {
    $old = $_SESSION[$key][$field] ?? $default;
    unset($_SESSION[$key][$field]);
    return htmlspecialchars($old);
}

// ─── Chat Client-Owner ─────────────────────────────────────────────────────────

/**
 * Ambil id_user owner utama (akun owner aktif dengan id terkecil).
 * Dipakai supaya semua pesan dari client selalu diarahkan ke satu owner yang sama.
 */
function getPrimaryOwnerId($conn): ?int {
    $result = mysqli_query($conn,
        "SELECT id_user FROM users WHERE role='owner' AND status='aktif' ORDER BY id_user ASC LIMIT 1"
    );
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int) $row['id_user'];
    }
    return null;
}