<div class="modal" id="bookingModal">

    <div class="modal-box">

        <div class="modal-header">

            <h2>Tambah Booking</h2>

            <span id="closeModal">&times;</span>

        </div>

        <form action="../process/booking/tambah_booking.php" method="POST">

            <label>Nama Client</label>
            <input
            type="text"
            name="nama_client"
            required>

            <label>Email</label>
            <input
            type="email"
            name="email"
            required>

            <label>No HP</label>
            <input
            type="text"
            name="telepon"
            required>

            <label>Lokasi</label>
            <input
            type="text"
            name="lokasi"
            required>

            <label>Tanggal Acara</label>
            <input
            type="date"
            name="tanggal_acara"
            required>

            <button type="submit">

                Simpan Booking

            </button>

        </form>

    </div>

</div>