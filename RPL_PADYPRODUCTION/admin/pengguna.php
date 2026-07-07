<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

/*==========================================
    AMBIL DATA USER
==========================================*/

$queryUser = mysqli_query($conn, "
    SELECT *
    FROM user
    ORDER BY created_at DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manajemen Pengguna</title>

    <link rel="stylesheet"
          href="../assets/css/dashboard.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box">

            <div style="display:flex;
                        justify-content:space-between;
                        align-items:center;
                        margin-bottom:20px;">

                <h3>Manajemen Pengguna</h3>

                <a href="tambah_pengguna.php"
                   class="btn-primary">

                    + Tambah Pengguna

                </a>

            </div>

            <div style="overflow-x:auto;">

                <table border="1"
                       cellpadding="10"
                       cellspacing="0"
                       width="100%">

                    <thead>

                        <tr>

                            <th width="5%">No</th>

                            <th>Nama</th>

                            <th>Email</th>

                            <th>No HP</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th width="18%">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    if(mysqli_num_rows($queryUser) > 0) :

                        $no = 1;

                        while($user = mysqli_fetch_assoc($queryUser)) :

                    ?>

                        <tr>

                            <td align="center">

                                <?= $no++; ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($user['nama']); ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($user['email']); ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($user['no_hp']); ?>

                            </td>

                            <td align="center">

                                <?= ucfirst($user['role']); ?>

                            </td>

                            <td align="center">

                                <?= ucfirst($user['status']); ?>

                            </td>

                            <td align="center">

                                <a href="edit_pengguna.php?id=<?= $user['id_user']; ?>">

                                    Edit

                                </a>

                                |

                                <?php

                                if(
                                    $user['role'] != "owner"
                                    &&
                                    $user['id_user'] != $_SESSION['id_user']
                                ) :

                                ?>

                                    <a href="../process/pengguna/hapus_pengguna.php?id=<?= $user['id_user']; ?>"
                                       onclick="return confirm('Yakin ingin menghapus pengguna ini?');">

                                        Hapus

                                    </a>

                                <?php else : ?>

                                    -

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php

                        endwhile;

                    else :

                    ?>

                        <tr>

                            <td colspan="7"
                                align="center">

                                Belum ada data pengguna.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script src="../assets/js/dashboard.js"></script>

</body>

</html>