<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transaksi Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?= formatted('currency', $data_statistik->today->transaksi_total) ?> (<?= formatted('currency', $data_statistik->today->transaksi_jumlah) ?>)
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-basket fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?= formatted('currency', $data_statistik->total->transaksi_total) ?> (<?= formatted('currency', $data_statistik->total->transaksi_jumlah) ?>)
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-basket fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Laba Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?= formatted('currency', $data_statistik->today->laba) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Laba</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?= formatted('currency', $data_statistik->total->laba) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Transaksi</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="grafik-transaksi"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Laba</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="grafik-laba"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const chart_label = ['<?= implode("', '", array_keys($data_chart)) ?>'];
        const chart_transaksi = [<?= implode(',', array_column($data_chart, 'transaksi')) ?>];
        const chart_laba = [<?= implode(',', array_column($data_chart, 'laba')) ?>];
    </script>