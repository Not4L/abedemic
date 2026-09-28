<?php
$pageTitle = 'Quiz Time';
$tab = 'Quiz Time';
$contentClass = 'quiz-content';
require __DIR__ . '/partials/header.php';
?>
			<img class="page-avatar" src="img/rimuru-mascot.jpeg" alt="">

			<div id="quizQuestion" class="quiz-stage">
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
	<script>
		// TODO Fase 3: array ini nanti diisi hasil generate Gemini, bukan dummy
		const questions = [
			{ q: 'Lorem ipsum dolor sit amet?', options: ['Opsi A', 'Opsi B', 'Opsi C', 'Opsi D'], correct: 1, explain: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' },
			{ q: 'Consectetur adipiscing elit?', options: ['Opsi A', 'Opsi B', 'Opsi C', 'Opsi D'], correct: 2, explain: 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.' },
			{ q: 'Ut labore et dolore magna aliqua?', options: ['Opsi A', 'Opsi B', 'Opsi C', 'Opsi D'], correct: 0, explain: 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.' }
		];
		let current = 0;

		const qStage = document.getElementById('quizQuestion');
		const eStage = document.getElementById('quizExplain');
		const qText = document.getElementById('quizQuestionText');
		const qOptions = document.getElementById('quizOptions');
		const eText = document.getElementById('quizExplainText');

		function renderQuestion() {
			const item = questions[current];
			qText.textContent = item.q;
			qOptions.innerHTML = '';
			const letters = ['A', 'B', 'C', 'D'];
			item.options.forEach((opt, i) => {
				const btn = document.createElement('button');
				btn.className = 'option';
				btn.textContent = letters[i] + '. ' + opt;
				btn.addEventListener('click', () => {
					eText.textContent = item.explain;
					qStage.hidden = true;
					eStage.hidden = false;
				});
				qOptions.appendChild(btn);
			});
		}

		document.getElementById('quizNext').addEventListener('click', () => {
			current = (current + 1) % questions.length;
			renderQuestion();
			eStage.hidden = true;
			qStage.hidden = false;
		});

		renderQuestion();
	</script>
<?php require __DIR__ . '/partials/footer.php'; ?>