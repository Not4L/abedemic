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
					<span>DROP FILE HERE</span>
				</label>
				<input type="file" id="summaryFile" hidden>
				<button type="button" class="login-button" id="summaryGenerate" disabled>Generate Summary</button>
			</div>

			<div id="summaryResult" class="summary-stage" hidden>
				<div class="result-panel">
					<h2>Ringkasan Materi</h2>
					<p id="summaryText">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</div>
				<button type="button" class="login-button" id="summaryReset">Ringkas File Lain</button>
			</div>
	<script>
		const fileInput = document.getElementById('summaryFile');
		const generateBtn = document.getElementById('summaryGenerate');
		const uploadStage = document.getElementById('summaryUpload');
		const resultStage = document.getElementById('summaryResult');

		fileInput.addEventListener('change', () => {
			generateBtn.disabled = !fileInput.files.length;
		});

		generateBtn.addEventListener('click', () => {
			// TODO Fase 3: kirim fileInput.files[0] ke api/summary.php (proxy Gemini)
			uploadStage.hidden = true;
			resultStage.hidden = false;
		});

		document.getElementById('summaryReset').addEventListener('click', () => {
			fileInput.value = '';
			generateBtn.disabled = true;
			resultStage.hidden = true;
			uploadStage.hidden = false;
		});
	</script>
<?php require __DIR__ . '/partials/footer.php'; ?>