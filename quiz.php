<?php
$pageTitle = 'Quiz Time';
$tab = 'Quiz Time';
$contentClass = 'quiz-content';
require __DIR__ . '/partials/header.php';
?>
				<img class="page-avatar" src="img/rimuru-mascot.jpeg" alt="">

				<div id="quizUpload" class="quiz-stage">
					<label class="dropzone" for="quizFile">
						<img src="img/folder-pic.jpeg" alt="">
						<span id="quizFileLabel">DROP FILE HERE</span>
					</label>
					<input type="file" id="quizFile" accept=".txt,.pdf,.png,.jpg,.jpeg,.webp" hidden>
					<p class="stage-error" id="quizError" hidden></p>
					<button type="button" class="login-button" id="quizGenerate" disabled>Generate Quiz</button>
				</div>

				<div id="quizLoading" class="quiz-stage" hidden>
					<div class="pixel-progress pixel-progress-lg"><div class="pixel-progress-fill"></div></div>
					<p class="loading-label">Abe sedang menyusun soal...</p>
				</div>

				<div id="quizQuestion" class="quiz-stage" hidden>
					<div class="quiz-box" id="quizQuestionText"></div>
					<div class="quiz-options" id="quizOptions"></div>
				</div>

				<div id="quizExplain" class="quiz-stage" hidden>
					<div class="quiz-explain-box">
						<h3>Explanation:</h3>
						<p id="quizExplainText"></p>
					</div>
					<button type="button" class="login-button" id="quizNext">Next Question</button>
				</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
	<script>
		const fileInput = document.getElementById('quizFile');
		const fileLabel = document.getElementById('quizFileLabel');
		const generateBtn = document.getElementById('quizGenerate');
		const errorBox = document.getElementById('quizError');
		const uploadStage = document.getElementById('quizUpload');
		const loadingStage = document.getElementById('quizLoading');
		const qStage = document.getElementById('quizQuestion');
		const eStage = document.getElementById('quizExplain');
		const qText = document.getElementById('quizQuestionText');
		const qOptions = document.getElementById('quizOptions');
		const eText = document.getElementById('quizExplainText');

		let questions = [];
		let current = 0;

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
				const res = await fetch('api/quiz.php', { method: 'POST', body: formData });
				const data = await res.json();

				if (!res.ok) {
					throw new Error(data.error || 'Gagal membuat kuis.');
				}

				questions = data.questions;
				current = 0;
				loadingStage.hidden = true;
				renderQuestion();
				qStage.hidden = false;
			} catch (err) {
				loadingStage.hidden = true;
				uploadStage.hidden = false;
				errorBox.textContent = err.message;
				errorBox.hidden = false;
			}
		});

		function renderQuestion() {
			const item = questions[current];
			qText.textContent = item.question;
			qOptions.innerHTML = '';
			const letters = ['A', 'B', 'C', 'D'];
			item.options.forEach((opt, i) => {
				const btn = document.createElement('button');
				btn.className = 'option';
				btn.textContent = letters[i] + '. ' + opt;
				btn.addEventListener('click', () => {
					eText.textContent = item.explanation;
					qStage.hidden = true;
					eStage.hidden = false;
				});
				qOptions.appendChild(btn);
			});
		}

		document.getElementById('quizNext').addEventListener('click', () => {
			current += 1;
			eStage.hidden = true;

			if (current >= questions.length) {
				// Habis, balik ke upload untuk materi baru.
				fileInput.value = '';
				fileLabel.textContent = 'DROP FILE HERE';
				generateBtn.disabled = true;
				uploadStage.hidden = false;
				return;
			}

			renderQuestion();
			qStage.hidden = false;
		});
	</script>
</body>
</html>