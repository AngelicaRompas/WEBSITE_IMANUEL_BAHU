<?php
session_start();

// Hapus semua data session
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

/* =========================================================
   FIX REDIRECT: Deteksi Otomatis Lingkungan Server
   ========================================================= */
if ($_SERVER['HTTP_HOST'] == 'localhost:8081' || $_SERVER['HTTP_HOST'] == 'localhost') {
    // Jika di Localhost, arahkan kembali dengan menyertakan nama folder projek Anda
    header("Location: /WEBSITE_IMANUEL/index.php");
} else {
    // Jika di Hosting (imanuelbahu.org), cukup mundur satu tingkat ke root domain
    header("Location: ../index.php");
}
exit;
?>