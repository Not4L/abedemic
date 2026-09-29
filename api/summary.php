<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../gemini.php';

header('Content-Type: application/json');
$user = current_user();

if ($user === null) {
	http_response_code(401);
	echo json_encode(['error' => 'Belum login.']);
	exit;
}

if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
	http_response_code(400);
	echo json_encode(['error' => 'File tidak diterima.']);
	exit;
}

$language = $user['language'] === 'en' ? 'English' : 'Bahasa Indonesia';
$systemPrompt = ABE_SYSTEM_PROMPT . "\n\nTugas kamu sekarang: buat ringkasan materi dari file yang diberikan. " .
	"Balas HANYA ringkasannya (boleh pakai sub-judul singkat), tanpa basa-basi pembuka/penutup. Jawab dalam $language.";

try {
	$filePart = file_to_gemini_part($_FILES['file']);
	$summary = call_gemini($systemPrompt, [$filePart]);
} catch (GeminiException $ex) {
	http_response_code(502);
	echo json_encode(['error' => $ex->getMessage()]);
	exit;
}

echo json_encode(['summary' => $summary]);