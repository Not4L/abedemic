<?php
// Koneksi database + session + fungsi bantu yang dipakai semua halaman.

session_start();

$dbHost = 'localhost';
$dbName = 'abedemic';
$dbUser = 'root';
$dbPass = '';

try {
	$pdo = new PDO(
		"mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
		$dbUser,
		$dbPass,
		[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		]
	);
} catch (PDOException $ex) {
	http_response_code(500);
	exit('Database belum siap. Pastikan MySQL di Laragon menyala dan database "abedemic" sudah di-import dari database.sql.');
}

// Escape teks sebelum dicetak ke HTML (mencegah XSS).
function e(?string $text): string
{
	return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Ambil data user yang sedang login, atau null kalau belum login.
function current_user(): ?array
{
	global $pdo;

	if (empty($_SESSION['user_id'])) {
		return null;
	}

	$stmt = $pdo->prepare('SELECT id, username, email, language, created_at FROM users WHERE id = ?');
	$stmt->execute([$_SESSION['user_id']]);
	$user = $stmt->fetch();

	return $user ?: null;
}

// Dipanggil di halaman yang wajib login. Belum login -> lempar ke login.php.
function require_login(): array
{
	$user = current_user();

	if ($user === null) {
		header('Location: login.php');
		exit;
	}

	return $user;
}

function language_label(string $code): string
{
	return $code === 'en' ? 'English' : 'Bahasa Indonesia';
}