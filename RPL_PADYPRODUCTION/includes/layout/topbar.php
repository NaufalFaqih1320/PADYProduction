<?php $role = $_SESSION['role'] ?? ''; ?>

<?php if ($role === 'crew'): ?>

    <!-- ======================================
         TOPBAR CREW
    ====================================== -->

    <header class="crew-topbar">

        <div class="crew-topbar-left">

            <img src="../assets/images/logo.png" alt="PADY Production">

            <div>
                <h1>PADYProduction</h1>
                <p>Dashboard Crew - Gudang</p>
            </div>

        </div>

        <a href="../logout.php" class="btn-keluar">Keluar</a>

    </header>

<?php elseif ($role === 'admin'): ?>

    <!-- ======================================
         TOPBAR ADMIN
    ====================================== -->

    <div class="topbar">

        <div>
            <h1>Dashboard Admin</h1>
            <p>Selamat Datang, <strong><?= $_SESSION['nama']; ?></strong></p>
        </div>

        <div class="owner-info">
            <h3><?= date("d F Y"); ?></h3>
        </div>

    </div>

<?php else: ?>

    <!-- ======================================
         TOPBAR OWNER (default)
    ====================================== -->

    <div class="topbar">

        <div>
            <h1>Dashboard Owner</h1>
            <p>Selamat Datang, <strong><?= $_SESSION['nama']; ?></strong></p>
        </div>

        <div class="owner-info">
            <h3><?= date("d F Y"); ?></h3>
        </div>

    </div>

<?php endif; ?>