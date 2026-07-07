<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

/*==========================================
    CEK ID
==========================================*/

if (!isset($_GET['id'])) {

    header("Location: pengguna.php");
    exit;
}

$id = (int) $_GET['id'];

/*==========================================
    AMBIL DATA USER
==========================================*/

$query = mysqli_query($conn,"
    SELECT *
    FROM user
    WHERE id_user = '$id'
");

if(mysqli_num_rows($query) == 0){

    header("Location: pengguna.php");
    exit;
}

$user = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Pengguna</title>

    <link rel="stylesheet"
          href="../assets/css/dashboard.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box">

            <h3>Edit Pengguna</h3>

            <br>

            <form action="../process/pengguna/edit_pengguna.php" method="POST">

                <input
                    type="hidden"
                    name="id_user"
                    value="<?= $user['id_user']; ?>">

                <table cellpadding="8" width="100%">

                    <tr>

                        <td width="20%">Nama</td>

                        <td>

                            <input
                                type="text"
                                name="nama"
                                value="<?= htmlspecialchars($user['nama']); ?>"
                                required
                                style="width:100%;padding:10px;">

                        </td>

                    </tr>

                    <tr>

                        <td>Email</td>

                        <td>

                            <input
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($user['email']); ?>"
                                required
                                style="width:100%;padding:10px;">

                        </td>

                    </tr>

                    <tr>

                        <td>Password Baru</td>

                        <td>

                            <input
                                type="password"
                                name="password"
                                placeholder="Kosongkan jika tidak diubah"
                                style="width:100%;padding:10px;">

                        </td>

                    </tr>

                    <tr>

                        <td>No HP</td>

                        <td>

                            <input
                                type="text"
                                name="no_hp"
                                value="<?= htmlspecialchars($user['no_hp']); ?>"
                                required
                                style="width:100%;padding:10px;">

                        </td>

                    </tr>

                    <tr>

                        <td>Role</td>

                        <td>

                            <select
                                name="role"
                                required
                                style="width:100%;padding:10px;">

                                <option value="owner" <?= ($user['role']=="owner") ? "selected" : ""; ?>>Owner</option>

                                <option value="admin" <?= ($user['role']=="admin") ? "selected" : ""; ?>>Admin</option>

                                <option value="crew" <?= ($user['role']=="crew") ? "selected" : ""; ?>>Crew</option>

                                <option value="client" <?= ($user['role']=="client") ? "selected" : ""; ?>>Client</option>

                            </select>

                        </td>

                    </tr>

                    <tr>

                        <td>Status</td>

                        <td>

                            <select
                                name="status"
                                required
                                style="width:100%;padding:10px;">

                                <option value="aktif" <?= ($user['status']=="aktif") ? "selected" : ""; ?>>
                                    Aktif
                                </option>

                                <option value="nonaktif" <?= ($user['status']=="nonaktif") ? "selected" : ""; ?>>
                                    Nonaktif
                                </option>

                            </select>

                        </td>

                    </tr>

                </table>

                <br>

                <button
                    type="submit"
                    class="btn-primary">

                    Update

                </button>

                <a
                    href="pengguna.php"
                    class="btn-danger"
                    style="margin-left:10px;">

                    Batal

                </a>

            </form>

        </div>

    </div>

</div>

<script src="../assets/js/dashboard.js"></script>

</body>

</html>