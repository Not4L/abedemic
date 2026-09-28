<?php
$pageTitle = 'Ask Our AI';
$tab = 'Ask Our AI';
$contentClass = 'chat-content';
require __DIR__ . '/partials/header.php';
?>
			<div class="chat-log" id="chatLog">
				<div class="msg msg-ai">
					<img class="chat-mascot" src="img/crocodile-mascot.jpeg" alt="">
					<p>Halo <?= e($user['username']) ?>! Tanyakan apa saja soal materi belajarmu, aku bantu jawab.</p>
				</div>
			</div>
			<form class="chat-form" id="chatForm">
				<input type="text" id="chatInput" placeholder="Tanyakan Apa Saja..." autocomplete="off" required>
				<button type="submit" class="chat-send" aria-label="Kirim">&#10148;</button>
			</form>
	<script>
		const log = document.getElementById('chatLog');
		const form = document.getElementById('chatForm');
		const input = document.getElementById('chatInput');

		function addMessage(text, sender) {
			const row = document.createElement('div');
			row.className = 'msg msg-' + sender;
			if (sender === 'ai') {
				row.innerHTML = '<img class="chat-mascot" src="img/crocodile-mascot.jpeg" alt=""><p></p>';
			} else {
				row.innerHTML = '<p></p>';
			}
			row.querySelector('p').textContent = text;
			log.appendChild(row);
			log.scrollTop = log.scrollHeight;
		}

		form.addEventListener('submit', (e) => {
			e.preventDefault();
			const text = input.value.trim();
			if (!text) return;
			addMessage(text, 'user');
			input.value = '';
			// TODO Fase 3: ganti dengan fetch ke api/chat.php (proxy Gemini)
			setTimeout(() => addMessage('(dummy) Jawaban AI akan tampil di sini setelah backend Gemini tersambung.', 'ai'), 500);
		});
	</script>
<?php require __DIR__ . '/partials/footer.php'; ?>