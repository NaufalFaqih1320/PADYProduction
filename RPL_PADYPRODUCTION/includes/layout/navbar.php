<header>

<nav class="navbar">

<div class="container nav-wrapper">

<div class="logo">

<!-- =======================================

GANTI DENGAN LOGO ASLI

assets/images/logo.png

======================================= -->

<img src="assets/images/logo.png"
     alt="PADY Production">

</div>


<ul class="menu">

<li>

<a href="#">

Beranda

</a>

</li>

<li>

<a href="#about">

Tentang Kami

</a>

</li>

<li>

<a href="#services">

Layanan

</a>

</li>

<li>

<a href="#portfolio">

Portofolio

</a>

</li>

</ul>


<div class="button-group">

<?php if (isset($_SESSION['login']) && $_SESSION['role'] === 'client'): ?>

<a href="#" id="openChatBtn" class="contact-btn">

Hubungi Kami

<span class="chat-btn-badge" id="chatBtnBadge"></span>

</a>

<span class="welcome-text">Selamat datang, <?= htmlspecialchars($_SESSION['nama']); ?></span>

<a href="logout.php" class="login-btn">Logout</a>

<?php else: ?>

<a href="login.php" class="contact-btn">

Hubungi Kami

</a>

<a href="login.php" class="login-btn"> Login </a>

<?php endif; ?>

</div>

</div>

</nav>

</header>