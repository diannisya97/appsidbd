<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tambahkan di bagian <head> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-control-geocoder/2.1.0/Control.Geocoder.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-control-geocoder/2.1.0/Control.Geocoder.js"></script>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sistem Informasi DBD Tasikmalaya</title>
    <link rel="icon" href="../img/kiri.png">

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-draw/1.0.4/leaflet.draw.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-draw/1.0.4/leaflet.draw.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>

    <script src="../assets/demo/chart-area-demo.js"></script>
    <script src="../assets/demo/chart-bar-demo.js"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="../js/scripts.js"></script>
    <link href="../css/styles.css" rel="stylesheet" />

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .dynamic-tooltip {
    position: absolute;
    background: rgba(0, 0, 0, 0.75);
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    pointer-events: none;
}
.tooltip-custom {
    position: relative;
    cursor: pointer;
}

.tooltip-custom::after {
    content: attr(title);
    position: absolute;
    display: none;
    background: rgba(0, 0, 0, 0.75);
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    pointer-events: none;
    transform: translate(-50%, -100%);
}

.tooltip-custom:hover::after {
    display: block;
}

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .table th, .table td {
        padding: 12px;
        text-align: left;
        vertical-align: middle;
    }

    .table th {
        background-color: #f8f9fc;
    }

    .category-header {
        background-color: #4e73df;
        color: white;
        text-align: center;
    }

    /* Responsive Layout */
    @media (max-width: 768px) {
        .table th, .table td {
            display: block;
            text-align: left;
            width: 100%;
            box-sizing: border-box;
        }
        
        .table tbody tr {
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid #e3e6f0;
            margin-bottom: 10px;
        }
        
        .table thead {
            display: none;
        }
        
        .table tbody tr:last-child {
            border-bottom: none;
        }
    }

    @media (min-width: 769px) {
        .table tbody tr {
            display: table-row;
        }

        #map {
            min-height: 300px;
        }
    }

    /* Additional Styling */
    .table th, .table td {
        border-color: #e3e6f0;
    }

    /* Map Styling */
    #map {
        border: 1px solid #e3e6f0;
        border-radius: 5px;
        margin-top: 15px;
    }
    @media only screen and (max-width: 600px) {
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
    }

    .card-header a {
        font-size: 0.8rem;
        padding: 5px 10px;
    }

    .header-title {
        font-size: 1rem;
        text-align: center;
    }

    .btn-sm {
        font-size: 0.8rem;
        padding: 5px 8px;
    }

    .fas {
        font-size: 0.8rem;
    }
}

/* Tampilan pada perangkat seluler */
@media (max-width: 767.98px) {
    .modal-dialog {
        max-width: 90%; /* Mengurangi lebar modal untuk perangkat kecil */
        margin: 1rem auto; /* Margin agar modal tidak terlalu dekat dengan tepi layar */
    }

    .modal-content {
        height: auto; /* Mengatur tinggi otomatis */
        max-height: 90vh; /* Maksimum tinggi modal 90% dari tinggi viewport */
        overflow: hidden; /* Menghilangkan scroll di luar modal */
    }

    .modal-body {
        max-height: calc(90vh - 56px - 56px); /* Mengatur tinggi maksimum modal-body */
        overflow-y: auto; /* Menambahkan scroll vertikal untuk konten panjang */
    }

    .modal-header, .modal-footer {
        background-color: #fff; /* Background modal header/footer */
        z-index: 1000; /* Menjaga header/footer tetap di atas konten */
    }

    .modal-footer {
        position: sticky;
        bottom: 0;
    }

    .modal-header {
        position: sticky;
        top: 0;
    }

    .modal-header h4 {
        font-size: 1.25rem; /* Ukuran font judul lebih kecil pada perangkat kecil */
    }

    .btn {
        font-size: 0.875rem; /* Ukuran font tombol lebih kecil */
        padding: 0.375rem 0.75rem; /* Padding tombol lebih kecil */
    }

    .form-control {
        font-size: 0.875rem; /* Ukuran font input lebih kecil */
    }
}
/* Tampilan pada perangkat seluler */
@media (max-width: 767.98px) {
    /* Heading */
    h1.mt-4 {
        font-size: 1.5rem; /* Mengatur ukuran font heading */
        text-align: center; /* Menyusun heading di tengah */
        margin-top: 1rem; /* Mengatur jarak atas */
        color: #333; /* Warna teks heading */
        font-weight: 600; /* Membuat font lebih tebal */
    }

    /* Breadcrumb Navigation */
    .breadcrumb {
        padding: 0.75rem 1rem; /* Padding untuk breadcrumb */
        background-color: #f8f9fa; /* Warna latar belakang breadcrumb */
        border-radius: 0.25rem; /* Membuat sudut breadcrumb sedikit melengkung */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan */
        margin-bottom: 1rem; /* Jarak bawah breadcrumb */
        font-size: 0.875rem; /* Ukuran font breadcrumb lebih kecil */
    }

    .breadcrumb-item {
        display: inline; /* Menyusun breadcrumb-item dalam satu baris */
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: " > "; /* Menambahkan pemisah antara item breadcrumb */
    }

    .breadcrumb-item a {
        color: #007bff; /* Warna link breadcrumb */
        text-decoration: none; /* Menghapus garis bawah pada link */
    }

    .breadcrumb-item a:hover {
        text-decoration: underline; /* Menambahkan garis bawah saat hover */
    }
}
.breadcrumb {
    background-color: #f8f9fa; /* Warna latar belakang terang */
    border: 1px solid #dee2e6; /* Border abu-abu terang */
    border-radius: 0.25rem; /* Membuat sudut membulat */
    padding: 8px 15px; /* Padding dalam breadcrumb */
    margin-bottom: 15px; /* Jarak bawah breadcrumb */
}

.breadcrumb-item a {
    color: #4e73df; /* Warna biru utama */
    text-decoration: none; /* Hilangkan garis bawah */
    font-weight: 500; /* Menambah ketebalan teks */
}

.breadcrumb-item a:hover {
    text-decoration: underline; /* Tambahkan garis bawah saat hover */
    color: #2e59d9; /* Warna biru lebih gelap saat hover */
}

.breadcrumb-item.active {
    color: #6c757d; /* Warna abu-abu untuk item aktif */
    font-weight: bold; /* Teks tebal untuk item aktif */
}
</style>
</head>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css"></script>

<script>
window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimplee = document.getElementById('datatablesSimplee');
    if (datatablesSimplee) {
        new simpleDatatables.DataTable(datatablesSimplee, {
            searchable: false, // Menonaktifkan pencarian
            perPageSelect: false // Menonaktifkan dropdown pemilihan jumlah entri per halaman
        });
    }
});

</script>