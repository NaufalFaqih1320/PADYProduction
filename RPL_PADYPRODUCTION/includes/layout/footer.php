<?php if (isset($_SESSION['login']) && $_SESSION['role'] === 'client'): ?>

<?php include __DIR__ . '/../components/chat_widget.php'; ?>

<script>
    window.CHAT_BASE_URL = "<?= BASE_URL; ?>";
</script>

<script src="assets/js/chat_widget.js"></script>

<?php endif; ?>

<script src="assets/js/script.js"></script>

</body>

</html>