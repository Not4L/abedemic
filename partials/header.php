<?php
// Pembuka semua halaman setelah login: cek login, <head>, frame, sidebar.
// Variabel yang bisa diisi halaman sebelum include: $pageTitle, $tab, $contentClass, $showStrip>.
// Tag penutup </section>, </div>, </main> lalu modal + script, kemudian </body></html>
// berada di partials/footer.php. Dengan demikian konten halaman (chat.php, summary.php,
// quiz.php, games.php) tetap berada di dalam <section class="content">, sementara modal
// berada di luar .app (anak <body>) agar tidak ter-clip oleh clip-path .app.
require_once __DIR__ . '/../config.php';

$user = require_login();

$pageTitle = $pageTitle ?? 'Abedemic';
$tab = $tab ?? null;
$contentClass = $contentClass ?? '';
$showStrip = $showStrip ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?> | Abedemic</title>
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="app.css">
</head>
<body>
	<main class="app">
		<header class="brand">ABEDEMIC</header>
		<?php if ($tab): ?>
			<div class="page-tab"><?= e($tab) ?></div>
		<?php endif; ?>
		<div class="layout">
			<aside class="sidebar">
				<button class="profile-btn" type="button" data-open="profileModal">
					<img class="profile-image" src="img/rimuru-mascot.jpeg" alt="">
					<span class="profile-name">Profile</span>
				</button>
				<button class="side-link" type="button" data-open="settingModal">
					<img src="img/folder-pic.jpeg" alt="">Setting
				</button>
				<?php if ($showStrip): ?>
					<div class="mascot-strip" aria-hidden="true">
						<img src="img/dino-mascot.jpeg" alt="">
						<img src="img/elf-mascot.jpeg" alt="">
					</div>
				<?php endif; ?>
			</aside>
			<section class="content <?= e($contentClass) ?>">