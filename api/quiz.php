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
$systemPrompt = ABE_SYSTEM_PROMPT . "\n\nTugas kamu sekarang: buat 5 soal pilihan ganda (A-D) dari materi yang diberikan, " .
	"lengkap dengan penjelasan jawaban. Jawab dalam $language.";

// Skema ini yang membuat Gemini WAJIB balas JSON dengan bentuk persis begini, bukan teks bebas.
$schema = [
	'type' => 'ARRAY',
	'items' => [
		'type' => 'OBJECT',
		'properties' => [
			'question' => ['type' => 'STRING'],
			'options' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING'], 'minItems' => 4, 'maxItems' => 4],
			'correctIndex' => ['type' => 'INTEGER'],
			'explanation' => ['type' => 'STRING'],
		],
		'required' => ['question', 'options', 'correctIndex', 'explanation'],
	],
];

try {
	$filePart = file_to_gemini_part($_FILES['file']);
	$raw = call_gemini($systemPrompt, [$filePart], $schema);
	$questions = json_decode($raw, true);

	if (!is_array($questions) || count($questions) === 0) {
		throw new GeminiException('Format kuis dari Gemini tidak terbaca.');
	}
} catch (GeminiException $ex) {
	http_response_code(502);
	echo json_encode(['error' => $ex->getMessage()]);
	exit;
}

echo json_encode(['questions' => $questions]);