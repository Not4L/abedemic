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

$input = json_decode(file_get_contents('php://input'), true);
$message = trim($input['message'] ?? '');

if ($message === '') {
	http_response_code(400);
	echo json_encode(['error' => 'Pertanyaan kosong.']);
	exit;
}
if (mb_strlen($message) > 2000) {
	http_response_code(400);
	echo json_encode(['error' => 'Pertanyaan terlalu panjang (maksimal 2000 karakter).']);
	exit;
}

// Riwayat singkat per sesi, supaya AI ingat konteks 6 pesan terakhir saja (hemat token).
$_SESSION['chat_history'] = $_SESSION['chat_history'] ?? [];

$parts = [];
foreach (array_slice($_SESSION['chat_history'], -6) as $turn) {
	$parts[] = ['text' => $turn['role'] . ': ' . $turn['text']];
}
$parts[] = ['text' => 'Siswa (' . $user['username'] . '): ' . $message];

$language = $user['language'] === 'en' ? 'English' : 'Bahasa Indonesia';
$systemPrompt = ABE_SYSTEM_PROMPT . "\n\nJawab dalam $language.";

try {
	$reply = call_gemini($systemPrompt, $parts);
} catch (GeminiException $ex) {
	http_response_code(502);
	echo json_encode(['error' => $ex->getMessage()]);
	exit;
}

$_SESSION['chat_history'][] = ['role' => 'Siswa', 'text' => $message];
$_SESSION['chat_history'][] = ['role' => 'Abe', 'text' => $reply];
$_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -20);

echo json_encode(['reply' => $reply]);