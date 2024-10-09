@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <!-- Card for Domains that haven't been paid -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Domain</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $countBelumTerbayar }}
                                <span class="text-warning text-sm font-weight-bolder">Item,</span>
                            </h5>
                            <h5 class="font-weight-bolder mb-0">
                                <span class="text-warning text-sm font-weight-bolder">belum terbayar</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Motorcycle Taxes nearing deadline -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Motor Pajak</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $countMotorPajakJatuhTempo }}
                                <span class="text-danger text-sm font-weight-bolder">item, jatuh tempo</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                            <i class="ni ni-delivery-fast text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for BTS nearing contract deadline -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">BTS Kontrak</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $countBtsJatuhTempo }}
                                <span class="text-warning text-sm font-weight-bolder">item, jatuh tempo</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                            <i class="ni ni-building text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Active Reminders -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Reminders Aktif</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $activeReminders }}
                                <span class="text-info text-sm font-weight-bolder">aktif</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-bell-55 text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Pending Reminders -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Reminders Belum Dilaksanakan</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $pendingReminders }}
                                <span class="text-warning text-sm font-weight-bolder">belum selesai</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                            <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Unpaid Payments -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Pembayaran</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $countPembayaranBelumLunas }}
                                <span class="text-danger text-sm font-weight-bolder">item, belum lunas</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                            <i class="ni ni-credit-card text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Trend Chart -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="paymentTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('dashboard')
<script>
    window.onload = function() {
        // Ambil data dari controller
        var transactionCounts = @json($transactionCounts);

        var ctx = document.getElementById("paymentTrendChart").getContext("2d");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Jumlah Transaksi",
                    tension: 0.4,
                    borderWidth: 3,
                    borderColor: "#0d6efd",
                    backgroundColor: "rgba(13, 110, 253, 0.1)",
                    data: transactionCounts,
                    maxBarThickness: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#9ca2b7",
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                        },
                        ticks: {
                            display: true,
                            color: "#9ca2b7",
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    }
</script>
@endpush
