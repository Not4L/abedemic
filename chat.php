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
					<input type="text" id="chatInput" placeholder="Tanyakan Apa Saja..." autocomplete="off" required maxlength="2000">
					<button type="submit" class="chat-send" id="chatSend" aria-label="Kirim">&#10148;</button>
				</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
	<script>
		const log = document.getElementById('chatLog');
		const form = document.getElementById('chatForm');
		const input = document.getElementById('chatInput');
		const sendBtn = document.getElementById('chatSend');

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
			return row;
		}

		function addLoadingBubble() {
			const row = document.createElement('div');
			row.className = 'msg msg-ai';
			row.innerHTML = '<img class="chat-mascot" src="img/crocodile-mascot.jpeg" alt="">' +
				'<div class="pixel-progress"><div class="pixel-progress-fill"></div></div>';
			log.appendChild(row);
			log.scrollTop = log.scrollHeight;
			return row;
		}

		form.addEventListener('submit', async (e) => {
			e.preventDefault();
			const text = input.value.trim();
			if (!text) return;

			addMessage(text, 'user');
			input.value = '';
			input.disabled = true;
			sendBtn.disabled = true;
			const loadingRow = addLoadingBubble();

			try {
				const res = await fetch('api/chat.php', {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify({ message: text })
				});
				const data = await res.json();
				loadingRow.remove();

				if (!res.ok) {
					addMessage(data.error || 'Terjadi kesalahan, coba lagi.', 'ai');
				} else {
					addMessage(data.reply, 'ai');
				}
			} catch (err) {
				loadingRow.remove();
				addMessage('Tidak bisa terhubung ke server. Cek koneksi lalu coba lagi.', 'ai');
			} finally {
				input.disabled = false;
				sendBtn.disabled = false;
				input.focus();
			}
		});
	</script>
</body>
</html>