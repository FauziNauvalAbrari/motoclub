<?php
if (file_exists('sidebar.php')) {
    include 'sidebar.php';
}
?>
    <style>
        .sidebar {
            position: fixed; 
        }
    </style>
</head>
<body>
<div class="content-areas">
    <div class="container-fluid">

        <h1 class="gallery-page-title">AGENDA ACARA & KEGIATAN</h1>
        <hr class="gallery-page-hr">

        <h2 class="event-section-title">Akan Datang</h2>
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="event-card">
                    <img src="img/g6.jpg" class="event-card-img-top" alt="Malam Apresiasi CMN">
                    <div class="card-body">
                        <h5 class="card-title">Malam Apresiasi & Penghargaan Anggota CMN 2025</h5>
                        <p class="event-meta">
                            <i class="fas fa-calendar-alt"></i> Sabtu, 20 Juli 2025<br>
                            <i class="fas fa-clock"></i> 19:00 WITA - Selesai<br>
                            <i class="fas fa-map-marker-alt"></i> Grand Clarion Hotel, Makassar
                        </p>
                        <p class="card-text">Malam penganugerahan bagi anggota berprestasi dan kontributif. Dimeriahkan pertunjukan musik dan makan malam bersama.</p>
                        <div class="event-status event-status-upcoming">Akan Datang</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="event-card">
                    <img src="img/g3.jpg" class="event-card-img-top" alt="Bakti Sosial CMN">
                    <div class="card-body">
                        <h5 class="card-title">CMN Peduli: Bakti Sosial Panti Asuhan</h5>
                        <p class="event-meta">
                            <i class="fas fa-calendar-alt"></i> Minggu, 18 Agustus 2025<br>
                            <i class="fas fa-clock"></i> 09:00 - 13:00 WITA<br>
                            <i class="fas fa-map-marker-alt"></i> Panti Asuhan Harapan Bunda, Makassar
                        </p>
                        <p class="card-text">Berbagi dan memberikan bantuan sembako, alat tulis, serta permainan edukatif untuk anak-anak panti.</p>
                        <div class="event-status event-status-upcoming">Akan Datang</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="event-card">
                    <img src="img/g5.jpg" class="event-card-img-top" alt="MUNAS CMN">
                    <div class="card-body">
                        <h5 class="card-title">Musyawarah Nasional (MUNAS) V CMN</h5>
                        <p class="event-meta">
                            <i class="fas fa-calendar-alt"></i> Jumat - Minggu, 13-15 September 2025<br>
                            <i class="fas fa-map-marker-alt"></i> Hotel Aryaduta, Makassar
                        </p>
                        <p class="card-text">Pemilihan ketua umum baru, pembahasan AD/ART, dan rencana strategis klub untuk periode mendatang.</p>
                        <div class="event-status event-status-upcoming">Akan Datang</div>
                    </div>
                </div>
            </div>
        </div> <hr class="my-5">

        <h2 class="event-section-title">Telah Selesai</h2>
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="event-card event-card-horizontal">
                    <img src="img/g4.jpg" class="event-card-img-top" alt="Touring Kemerdekaan">
                    <div class="card-body">
                        <h5 class="card-title">Touring Kemerdekaan Lintas Sulawesi 2024</h5>
                        <p class="event-meta">
                            <i class="fas fa-calendar-alt"></i> 15-17 Agustus 2024<br>
                            <i class="fas fa-map-marker-alt"></i> Rute: Makassar - Toraja
                        </p>
                        <p class="card-text">Touring bersama merayakan hari kemerdekaan sambil menikmati keindahan alam Sulawesi Selatan dan mempererat persaudaraan.</p>
                        <div class="event-status event-status-finished">Telah Selesai</div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6">
                <div class="event-card">
                    <img src="img/g2.jpg" class="event-card-img-top" alt="Workshop Safety Riding">
                    <div class="card-body">
                        <h5 class="card-title">Workshop Safety Riding & Basic Maintenance</h5>
                        <p class="event-meta">
                            <i class="fas fa-calendar-alt"></i> Sabtu, 23 Maret 2024<br>
                            <i class="fas fa-clock"></i> 10:00 - 15:00 WITA<br>
                            <i class="fas fa-map-marker-alt"></i> Sekretariat CMN Makassar
                        </p>
                        <p class="card-text">Pelatihan keselamatan berkendara dan perawatan dasar motor bagi anggota baru dan umum.</p>
                        <div class="event-status event-status-finished">Telah Selesai</div>
                    </div>
                </div>
            </div>
             </div> </div> </div> <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>