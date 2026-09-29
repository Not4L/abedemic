<?php
$pageTitle = 'Summary';
$tab = 'Summary';
$contentClass = 'summary-content';
require __DIR__ . '/partials/header.php';
?>
				<img class="page-avatar" src="img/rimuru-mascot.jpeg" alt="">

				<div id="summaryUpload" class="summary-stage">
					<label class="dropzone" for="summaryFile">
						<img src="img/folder-pic.jpeg" alt="">
						<span id="summaryFileLabel">DROP FILE HERE</span>
					</label>
					<input type="file" id="summaryFile" accept=".txt,.pdf,.png,.jpg,.jpeg,.webp" hidden>
					<p class="stage-error" id="summaryError" hidden></p>
					<button type="button" class="login-button" id="summaryGenerate" disabled>Generate Summary</button>
				</div>

				<div id="summaryLoading" class="summary-stage" hidden>
					<div class="pixel-progress pixel-progress-lg"><div class="pixel-progress-fill"></div></div>
					<p class="loading-label">Abe sedang membaca materimu...</p>
				</div>

				<div id="summaryResult" class="summary-stage" hidden>
					<div class="result-panel">
						<h2>Ringkasan Materi</h2>
						<p id="summaryText"></p>
					</div>
					<button type="button" class="login-button" id="summaryReset">Ringkas File Lain</button>
				</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
	<script>
		const fileInput = document.getElementById('summaryFile');
		const fileLabel = document.getElementById('summaryFileLabel');
		const generateBtn = document.getElementById('summaryGenerate');
		const errorBox = document.getElementById('summaryError');
		const uploadStage = document.getElementById('summaryUpload');
		const loadingStage = document.getElementById('summaryLoading');
		const resultStage = document.getElementById('summaryResult');

		fileInput.addEventListener('change', () => {
			generateBtn.disabled = !fileInput.files.length;
			fileLabel.textContent = fileInput.files.length ? fileInput.files[0].name : 'DROP FILE HERE';
		});

		generateBtn.addEventListener('click', async () => {
			errorBox.hidden = true;
			uploadStage.hidden = true;
			loadingStage.hidden = false;

			const formData = new FormData();
			formData.append('file', fileInput.files[0]);

			try {
				const res = await fetch('api/summary.php', { method: 'POST', body: formData });
				const data = await res.json();

				if (!res.ok) {
					throw new Error(data.error || 'Gagal membuat ringkasan.');
				}

				document.getElementById('summaryText').textContent = data.summary;
				loadingStage.hidden = true;
				resultStage.hidden = false;
			} catch (err) {
				loadingStage.hidden = true;
				uploadStage.hidden = false;
				errorBox.textContent = err.message;
				errorBox.hidden = false;
			}
		});

		document.getElementById('summaryReset').addEventListener('click', () => {
			fileInput.value = '';
			fileLabel.textContent = 'DROP FILE HERE';
			generateBtn.disabled = true;
			resultStage.hidden = true;
			uploadStage.hidden = false;
		});
	</script>
</body>
</html>