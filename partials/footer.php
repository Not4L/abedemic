<?php
// Penutup halaman: tutup </section>, </div>, </main>, lalu modal (anak <body> agar tidak
// ter-clip oleh clip-path .app), script, dan finally </body></html>.
// Dipanggil oleh chat.php, summary.php, quiz.php, games.php setelah isi konten.
?>
			</section>
		</div>
	</main>

<!-- Modal Profile -->
<div class="modal" id="profileModal" hidden>
	<div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="profileTitle">
		<button class="modal-close" type="button" data-close aria-label="Tutup">&times;</button>
		<img class="modal-avatar" src="img/rimuru-mascot.jpeg" alt="">
		<h2 id="profileTitle"><?= e($user['username']) ?></h2>
		<dl class="modal-info">
			<dt>Email</dt>
			<dd><?= e($user['email']) ?></dd>
			<dt>Bahasa</dt>
			<dd><?= e(language_label($user['language'])) ?></dd>
			<dt>Bergabung</dt>
			<dd><?= e(date('d M Y', strtotime($user['created_at']))) ?></dd>
		</dl>
		<a class="login-button" href="logout.php">Log Out</a>
	</div>
</div>

<!-- Modal Setting -->
<div class="modal" id="settingModal" hidden>
	<div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="settingTitle">
		<button class="modal-close" type="button" data-close aria-label="Tutup">&times;</button>
		<h2 id="settingTitle">Setting</h2>
		<form action="save_settings.php" method="post">
			<input type="hidden" name="back" value="<?= e(basename($_SERVER['SCRIPT_NAME'])) ?>">
			<label class="modal-label" for="languageSelect">Bahasa jawaban AI</label>
			<select class="modal-select" id="languageSelect" name="language">
				<option value="id" <?= $user['language'] === 'id' ? 'selected' : '' ?>>Bahasa Indonesia</option>
				<option value="en" <?= $user['language'] === 'en' ? 'selected' : '' ?>>English</option>
			</select>
			<button class="login-button" type="submit">Simpan</button>
		</form>
	</div>
</div>

<script>
	// Buka modal lewat tombol ber-atribut data-open, tutup lewat tombol X, klik area gelap, atau Esc.
	document.querySelectorAll('[data-open]').forEach((btn) => {
		btn.addEventListener('click', () => {
			document.getElementById(btn.dataset.open).hidden = false;
		});
	});

	document.querySelectorAll('.modal').forEach((modal) => {
		modal.addEventListener('click', (e) => {
			if (e.target === modal || e.target.hasAttribute('data-close')) {
				modal.hidden = true;
			}
		});
	});

	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			document.querySelectorAll('.modal').forEach((modal) => { modal.hidden = true; });
		}
	});
</script>
</body>
</html>