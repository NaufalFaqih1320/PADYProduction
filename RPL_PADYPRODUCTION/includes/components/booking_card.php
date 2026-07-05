<?php if(mysqli_num_rows($queryBooking) > 0): ?>

<?php while($booking = mysqli_fetch_assoc($queryBooking)): ?>

<?php

$queryDetail = mysqli_query($conn, "
    SELECT
        i.nama_barang,
        bd.jumlah
    FROM booking_detail bd
    JOIN inventaris i
        ON bd.id_inventaris = i.id_inventaris
    WHERE bd.id_booking = {$booking['id_booking']}
");

?>

<div class="booking-card"
    data-search="<?= strtolower(
        $booking['nama_acara'].' '.
        $booking['nama_client'].' '.
        $booking['email_client'].' '.
        $booking['lokasi']
    ); ?>">

    <div class="booking-header">

        <div>

            <h3><?= htmlspecialchars($booking['nama_acara']); ?></h3>

            <small>

                <?= date("d F Y", strtotime($booking['tanggal_acara'])); ?>

            </small>

        </div>

    </div>

    <div class="booking-body">

        <div class="booking-item">

            <label>Client</label>

            <p><?= htmlspecialchars($booking['nama_client']); ?></p>

        </div>

        <div class="booking-item">

            <label>Email</label>

            <p><?= htmlspecialchars($booking['email_client']); ?></p>

        </div>

        <div class="booking-item">

            <label>No. HP</label>

            <p><?= htmlspecialchars($booking['no_hp']); ?></p>

        </div>

        <div class="booking-item">

            <label>Lokasi</label>

            <p><?= htmlspecialchars($booking['lokasi']); ?></p>

        </div>

        <div class="booking-item">

            <label>Waktu</label>

            <p><?= htmlspecialchars($booking['waktu_acara']); ?></p>

        </div>

        <div class="booking-item">

            <label>Catatan</label>

            <p><?= !empty($booking['catatan']) ? htmlspecialchars($booking['catatan']) : "-"; ?></p>

        </div>

        <div class="booking-item full">

    <label>Alat Disewa</label>

    <ul class="booking-tools">

        <?php while($detail = mysqli_fetch_assoc($queryDetail)) : ?>

            <li>

                <?= htmlspecialchars($detail['nama_barang']); ?>

                (<?= $detail['jumlah']; ?> unit)

            </li>

        <?php endwhile; ?>

    </ul>

</div>

    </div>

<div class="booking-status">

    <label>Status</label>

    <span class="status <?= strtolower($booking['status']); ?>">

        <?= htmlspecialchars($booking['status']); ?>

    </span>

</div>

    <div class="booking-action">

    <?php if($booking['status'] == 'Pending') : ?>

        <a
            href="edit_booking.php?id=<?= $booking['id_booking']; ?>"
            class="btn-edit">

            Edit

        </a>

        <a
            href="../process/booking/selesai_booking.php?id=<?= $booking['id_booking']; ?>"
            class="btn-finish"
            onclick="return confirm('Booking sudah selesai?')">

            Selesai

        </a>

    <?php endif; ?>

    <a
        href="../process/booking/hapus_booking.php?id=<?= $booking['id_booking']; ?>"
        class="btn-delete"
        onclick="return confirm('Yakin ingin menghapus booking ini?')">

        Hapus

    </a>

</div>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="booking-card">

    <h3>Belum ada data booking.</h3>

</div>

<?php endif; ?>