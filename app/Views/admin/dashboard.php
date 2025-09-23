<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Toko Kalina</h1>
        <a href="<?= base_url('admin/dashboard/exportLaporan') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Laporan Penjualan
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Total Penjualan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penjualan (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_penjualan_bulan, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan Tahunan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan (Tahun Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_penjualan_tahun, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Diproses Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pesanan Diproses
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $jumlah_pesanan_diproses ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: <?= round($persentase_pesanan_diproses) ?>%" aria-valuenow="<?= round($persentase_pesanan_diproses) ?>" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Pending Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Pembayaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_menunggu_pembayaran ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pendapatan Bulanan</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opsi Laporan:</div>
                            <a class="dropdown-item" href="<?= base_url('admin/laporan/exportPDF/bulanan') ?>">Ekspor ke PDF</a>
                            <a class="dropdown-item" href="<?= base_url('admin/laporan/exportExcel/bulanan') ?>">Ekspor ke Excel</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori Produk Terlaris</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opsi Grafik:</div>
                            <a class="dropdown-item" href="<?= base_url('admin/laporan/kategori?limit=5') ?>">Tampilkan 5 Teratas</a>
                            <a class="dropdown-item" href="<?= base_url('admin/laporan/kategori?limit=10') ?>">Tampilkan 10 Teratas</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <?php foreach($kategori_warna as $kategori => $warna): ?>
                        <span class="mr-2">
                            <i class="fas fa-circle <?= $warna ?>"></i> <?= $kategori ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Pesanan Terbaru -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pesanan_terbaru as $pesanan): ?>
                                <tr>
                                    <td><?= $pesanan['id'] ?></td>
                                    <td><?= $pesanan['nama_pelanggan'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($pesanan['tanggal_pesanan'])) ?></td>
                                    <td>Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $badge_class = '';
switch($pesanan['status']) {
    case 'menunggu_pembayaran':
        $badge_class = 'badge-warning';
        $status_text = 'Menunggu Pembayaran';
        break;
    case 'diproses':
        $badge_class = 'badge-info';
        $status_text = 'Diproses';
        break;
    case 'dikirim':
        $badge_class = 'badge-primary';
        $status_text = 'Dikirim';
        break;
    case 'selesai':
        $badge_class = 'badge-success';
        $status_text = 'Selesai';
        break;
    case 'dibatalkan':
        $badge_class = 'badge-danger';
        $status_text = 'Dibatalkan';
        break;
    default:
        $badge_class = 'badge-secondary';
        $status_text = 'Unknown';
        break;
}
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= $status_text ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/pesanan/detail/'.$pesanan['id']) ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Stok Menipis & Preorder Cards -->
        <div class="col-lg-4">
            <!-- Produk Stok Menipis -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Produk Stok Menipis</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($produk_stok_menipis)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada produk dengan stok menipis</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($produk_stok_menipis as $produk): ?>
                                    <tr>
                                        <td><?= $produk['nama_produk'] ?></td>
                                        <td>
                                            <?php if($produk['stok'] <= 3): ?>
                                                <span class="text-danger font-weight-bold"><?= $produk['stok'] ?></span>
                                            <?php else: ?>
                                                <span class="text-warning"><?= $produk['stok'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/produk/edit/'.$produk['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Pesanan Pre-order -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pesanan Pre-order</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Estimasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($pesanan_preorder)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada pesanan pre-order</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($pesanan_preorder as $preorder): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('admin/pesanan/detail/'.$preorder['id']) ?>">
                                                <?= $preorder['id'] ?>
                                            </a>
                                        </td>
                                        <td><?= $preorder['nama_pelanggan'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($preorder['tanggal_estimasi_pengiriman'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Row -->
    <div class="row">
        <!-- Produk Terlaris -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Produk Terlaris</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah Terjual</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($produk_terlaris)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data penjualan produk</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($produk_terlaris as $produk): ?>
                                    <tr>
                                        <td><?= $produk['id'] ?></td>
                                        <td><?= $produk['nama_produk'] ?></td>
                                        <td><?= $produk['jumlah_terjual'] ?> unit</td>
                                        <td>
                                            <a href="<?= base_url('admin/produk/detail/'.$produk['id']) ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metode Pembayaran dan Statistik Pelanggan -->
        <div class="col-lg-4">
            <!-- Metode Pembayaran -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Metode Pembayaran Populer</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Metode</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $metode_data = json_decode($metode_pembayaran, true);
                                if(empty($metode_data)): 
                                ?>
                                <tr>
                                    <td colspan="2" class="text-center">Belum ada data pembayaran</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($metode_data as $metode): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            // Konversi payment_type ke label yang lebih ramah
                                            $payment_label = '';
                                            switch($metode['payment_type']) {
                                                case 'bank_transfer':
                                                    $payment_label = 'Transfer Bank';
                                                    break;
                                                case 'credit_card':
                                                    $payment_label = 'Kartu Kredit';
                                                    break;
                                                case 'gopay':
                                                    $payment_label = 'GoPay';
                                                    break;
                                                case 'shopeepay':
                                                    $payment_label = 'ShopeePay';
                                                    break;
                                                case 'ovo':
                                                    $payment_label = 'OVO';
                                                    break;
                                                default:
                                                    $payment_label = ucfirst($metode['payment_type']);
                                            }
                                            ?>
                                            <?= $payment_label ?>
                                        </td>
                                        <td><?= $metode['jumlah'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Statistik Ringkas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Ringkas</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-info"></i>
                        </div>
                        <div class="col">
                            <div class="font-weight-bold">Total Pelanggan</div>
                            <div class="h4"><?= $total_pelanggan ?></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                        <div class="col">
                            <div class="font-weight-bold">Rating Toko</div>
                            <div class="h4"><?= $rating_rata_rata ?> / 5</div>
                        </div>
                    </div>
                    <hr>
                    <a href="<?= base_url('admin/laporan/lengkap') ?>" class="btn btn-primary btn-block">
                        <i class="fas fa-chart-bar mr-1"></i> Lihat Statistik Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?= view('layouts/admin/footer'); ?>

<!-- JavaScript untuk Chart -->
<script>
// Mendapatkan data dari controller
const penjualanBulanan = <?= $penjualan_bulanan ?>;
const kategoriTerlaris = <?= $kategori_terlaris ?>;
const metodePembayaran = <?= $metode_pembayaran ?>;

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Area Chart - Penjualan Bulanan
function number_format(number, decimals, dec_point, thousands_sep) {
    // Format angka dengan separator ribuan
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

// Area Chart
const ctx = document.getElementById("myAreaChart");
const myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: penjualanBulanan.map(item => item.bulan),
        datasets: [{
            label: "Pendapatan",
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: penjualanBulanan.map(item => item.total),
        }],
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                    maxTicksLimit: 5,
                    padding: 10,
                    // Format angka sebagai mata uang
                    callback: function(value, index, values) {
                        return 'Rp ' + number_format(value);
                    }
                },
                gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }],
        },
        legend: {
            display: false
        },
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                    return datasetLabel + ': Rp ' + number_format(tooltipItem.yLabel);
                }
            }
        }
    }
});

// Pie Chart
const ctxPie = document.getElementById("myPieChart");
const myPieChart = new Chart(ctxPie, {
    type: 'doughnut',
    data: {
        labels: kategoriTerlaris.map(item => item.nama_kategori),
        datasets: [{
            data: kategoriTerlaris.map(item => item.jumlah_terjual),
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        },
        legend: {
            display: false
        },
        cutoutPercentage: 80,
    },
});
</script>