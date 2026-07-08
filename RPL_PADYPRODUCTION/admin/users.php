<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

$currentTab = 'users';

$queryUsers = mysqli_query($conn, "
    SELECT *
    FROM users
    ORDER BY created_at DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin</title>
    <link rel="stylesheet" href="../assets/css/crew.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "../includes/layout/topbar.php"; ?>

<?php include "../includes/components/admin_tabs.php"; ?>

<div class="crew-content">

    <div class="crew-page-header">

        <h2>Kelola Pengguna</h2>

        <button type="button" class="btn-add-inventaris" data-open-modal="modalTambahUser">
            + Tambah Pengguna
        </button>

    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="admin-alert success"><?= htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="admin-alert error"><?= htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <!-- TOOLBAR: SEARCH + FILTER ROLE -->
    <div class="crew-toolbar">

        <div class="crew-search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
                type="text"
                id="searchUser"
                placeholder="Cari nama atau email...">
        </div>

        <div class="crew-filter-pills" id="rolePills">
            <button type="button" class="pill active" data-role="semua">Semua</button>
            <button type="button" class="pill" data-role="owner">Owner</button>
            <button type="button" class="pill" data-role="admin">Admin</button>
            <button type="button" class="pill" data-role="crew">Crew</button>
            <button type="button" class="pill" data-role="client">Client</button>
        </div>

    </div>

    <div class="user-table-wrap">

        <table class="user-table">

            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (mysqli_num_rows($queryUsers) > 0): ?>

                    <?php while ($u = mysqli_fetch_assoc($queryUsers)): ?>

                        <tr class="user-row"
                            data-role="<?= strtolower($u['role']); ?>"
                            data-search="<?= strtolower(htmlspecialchars($u['nama'] . ' ' . $u['email'])); ?>">

                            <td><?= htmlspecialchars($u['nama']); ?></td>
                            <td><?= htmlspecialchars($u['email']); ?></td>
                            <td><?= htmlspecialchars($u['no_hp'] ?? '-'); ?></td>
                            <td>
                                <span class="role-badge <?= strtolower($u['role']); ?>">
                                    <?= htmlspecialchars($u['role']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-pill <?= strtolower($u['status']); ?>">
                                    <?= htmlspecialchars($u['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="user-table-actions">

                                    <button
                                        type="button"
                                        class="btn-edit btn-edit-user"
                                        data-id="<?= $u['id_user']; ?>"
                                        data-nama="<?= htmlspecialchars($u['nama'], ENT_QUOTES); ?>"
                                        data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES); ?>"
                                        data-no-hp="<?= htmlspecialchars($u['no_hp'] ?? '', ENT_QUOTES); ?>"
                                        data-role="<?= $u['role']; ?>"
                                        data-status="<?= $u['status']; ?>">
                                        Edit
                                    </button>

                                    <a
                                        href="../process/admin/hapus_user.php?id=<?= $u['id_user']; ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus pengguna <?= htmlspecialchars(addslashes($u['nama'])); ?>?')">
                                        Hapus
                                    </a>

                                </div>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" style="text-align:center; color:#888; padding:30px;">
                            Belum ada data pengguna.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<!-- MODAL TAMBAH PENGGUNA -->
<div class="modal-overlay" id="modalTambahUser">

    <div class="modal-box">

        <div class="modal-header">
            <h3>Tambah Pengguna</h3>
            <button type="button" class="modal-close" data-close-modal="modalTambahUser">&times;</button>
        </div>

        <form action="../process/admin/tambah_user.php" method="POST">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="6">
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="admin">Admin</option>
                        <option value="owner">Owner</option>
                        <option value="crew">Crew</option>
                        <option value="client">Client</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="modal-action">
                <button type="button" class="btn-cancel" data-close-modal="modalTambahUser">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>

        </form>

    </div>

</div>

<!-- MODAL EDIT PENGGUNA -->
<div class="modal-overlay" id="modalEditUser">

    <div class="modal-box">

        <div class="modal-header">
            <h3>Edit Pengguna</h3>
            <button type="button" class="modal-close" data-close-modal="modalEditUser">&times;</button>
        </div>

        <form action="../process/admin/edit_user.php" method="POST">

            <input type="hidden" name="id_user">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp">
            </div>

            <div class="form-group">
                <label>Password Baru (opsional)</label>
                <input type="password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah">
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="admin">Admin</option>
                        <option value="owner">Owner</option>
                        <option value="crew">Crew</option>
                        <option value="client">Client</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="modal-action">
                <button type="button" class="btn-cancel" data-close-modal="modalEditUser">Batal</button>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </div>

        </form>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="../assets/js/admin.js"></script>

</body>
</html>
