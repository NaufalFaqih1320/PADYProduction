document.addEventListener("DOMContentLoaded", function () {

    /* ================================
       FILTER KATEGORI (PILL)
    ================================ */

    const pills = document.querySelectorAll(".pill");
    const cards = document.querySelectorAll(".inventaris-card[data-kategori]");

    pills.forEach(function (pill) {

        pill.addEventListener("click", function () {

            pills.forEach(p => p.classList.remove("active"));
            pill.classList.add("active");

            const kategori = pill.dataset.kategori;

            cards.forEach(function (card) {
                const cocok = kategori === "semua" || card.dataset.kategori === kategori;
                card.style.display = cocok ? "" : "none";
            });

        });

    });

    /* ================================
       MODAL TAMBAH INVENTARIS
    ================================ */

    const modal = document.getElementById("modalTambah");
    const btnBuka = document.getElementById("btnTambahInventaris");
    const btnTutup = document.getElementById("btnTutupModal");
    const btnBatal = document.getElementById("btnBatalModal");

    if (modal && btnBuka) {

        btnBuka.addEventListener("click", () => modal.classList.add("show"));

        [btnTutup, btnBatal].forEach(function (btn) {
            if (btn) {
                btn.addEventListener("click", () => modal.classList.remove("show"));
            }
        });

        modal.addEventListener("click", function (e) {
            if (e.target === modal) {
                modal.classList.remove("show");
            }
        });

    }

    /* ================================
       BOOKING CREW: SEARCH + FILTER STATUS
    ================================ */

    const searchBookingCrew = document.getElementById("searchBookingCrew");
    const statusPillsBooking = document.querySelectorAll("#statusPills .pill");
    const bookingCards = document.querySelectorAll(".booking-crew-card[data-status]");

    function filterBookingCrew() {

        const keyword = (searchBookingCrew?.value || "").toLowerCase().trim();
        const activePill = document.querySelector("#statusPills .pill.active");
        const status = activePill ? activePill.dataset.status : "semua";

        bookingCards.forEach(function (card) {

            const cocokStatus = status === "semua" || card.dataset.status === status;
            const cocokSearch = keyword === "" || card.dataset.search.includes(keyword);

            card.style.display = (cocokStatus && cocokSearch) ? "" : "none";

        });

    }

    if (searchBookingCrew) {
        searchBookingCrew.addEventListener("input", filterBookingCrew);
    }

    statusPillsBooking.forEach(function (pill) {

        pill.addEventListener("click", function () {
            statusPillsBooking.forEach(p => p.classList.remove("active"));
            pill.classList.add("active");
            filterBookingCrew();
        });

    });

    /* ================================
       JADWAL: SWITCH SUBTAB (List <-> Kalender)
    ================================ */

    const jadwalSubtabs = document.querySelectorAll(".jadwal-subtab");
    const jadwalContents = document.querySelectorAll(".jadwal-tabcontent");

    jadwalSubtabs.forEach(function (tab) {

        tab.addEventListener("click", function () {

            jadwalSubtabs.forEach(t => t.classList.remove("active"));
            jadwalContents.forEach(c => c.style.display = "none");

            tab.classList.add("active");
            document.getElementById(tab.dataset.target).style.display = "block";

        });

    });

    if (window.location.hash === "#tabKalender") {

        jadwalSubtabs.forEach(t => t.classList.remove("active"));
        jadwalContents.forEach(c => c.style.display = "none");

        const kalenderTab = document.querySelector('.jadwal-subtab[data-target="tabKalender"]');
        if (kalenderTab) {
            kalenderTab.classList.add("active");
            document.getElementById("tabKalender").style.display = "block";
        }

    }

    /* ================================
       JADWAL: SEARCH + FILTER STATUS (Agenda)
    ================================ */

    const searchJadwalCrew = document.getElementById("searchJadwalCrew");
    const statusPillsJadwal = document.querySelectorAll("#statusPillsJadwal .pill");
    const jadwalCrewCards = document.querySelectorAll(".jadwal-crew-card[data-status]");

    function filterJadwalCrew() {

        const keyword = (searchJadwalCrew?.value || "").toLowerCase().trim();
        const activePill = document.querySelector("#statusPillsJadwal .pill.active");
        const status = activePill ? activePill.dataset.status : "semua";

        jadwalCrewCards.forEach(function (card) {

            const cocokStatus = status === "semua" || card.dataset.status === status;
            const cocokSearch = keyword === "" || card.dataset.search.includes(keyword);

            card.style.display = (cocokStatus && cocokSearch) ? "" : "none";

        });

    }

    if (searchJadwalCrew) {
        searchJadwalCrew.addEventListener("input", filterJadwalCrew);
    }

    statusPillsJadwal.forEach(function (pill) {

        pill.addEventListener("click", function () {
            statusPillsJadwal.forEach(p => p.classList.remove("active"));
            pill.classList.add("active");
            filterJadwalCrew();
        });

    });

    /* ================================
       JADWAL: KLIK TANGGAL DI KALENDER
    ================================ */

    const calendarDaysCrew = document.querySelectorAll(".calendar-day.has-event");
    const detailBoxCrew = document.getElementById("calendarDetailCrew");

    calendarDaysCrew.forEach(function (day) {

        day.addEventListener("click", function () {

            const events = JSON.parse(day.dataset.events);
            const tanggal = day.dataset.tanggal;

            let html = `<h4>${tanggal}</h4>`;

            events.forEach(function (ev) {
                html += `
                    <div class="jadwal-crew-card">
                        <div class="jadwal-crew-time">${ev.waktu_acara}</div>
                        <div class="jadwal-crew-body">
                            <h3>${ev.nama_acara}</h3>
                            <p><strong>Client:</strong> ${ev.nama_client}</p>
                            <p><strong>Lokasi:</strong> ${ev.lokasi}</p>
                            <span class="status ${ev.status.toLowerCase()}">${ev.status}</span>
                        </div>
                    </div>
                `;
            });

            detailBoxCrew.innerHTML = html;
            detailBoxCrew.scrollIntoView({ behavior: "smooth", block: "nearest" });

        });

    });

});