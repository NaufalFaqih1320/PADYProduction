<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tambah Pengguna</title>

    <link rel="stylesheet"
          href="../assets/css/dashboard.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box">

            <h3>Tambah Pengguna</h3>

            <br>

            <form action="../process/pengguna/tambah_pengguna.php" method="POST">

                <table cellpadding="8" width="100%">

                    <tr>

                        <td width="20%">Nama</td>

                        <td>

                            <input
                                type="text"
                                name="nama"
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
                                required
                                style="width:100%;padding:10px;">

                        </td>

                    </tr>

                    <tr>

                        <td>Password</td>

                        <td>

                            <input
                                type="password"
                                name="password"
                                required
                                style="width:100%;padding:10px;">

                        </td>

                    </tr>

                    <tr>

                        <td>No HP</td>

                        <td>

                            <input
                                type="text"
                                name="no_hp"
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

                                <option value="">-- Pilih Role --</option>

                                <option value="owner">Owner</option>

                                <option value="admin">Admin</option>

                                <option value="crew">Crew</option>

                                <option value="client">Client</option>

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

                                <option value="aktif">Aktif</option>

                                <option value="nonaktif">Nonaktif</option>

                            </select>

                        </td>

                    </tr>

                </table>

                <br>

                <button
                    type="submit"
                    class="btn-primary">

                    Simpan

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