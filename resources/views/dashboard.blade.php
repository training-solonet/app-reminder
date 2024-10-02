@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <!-- Card untuk Domain yang belum dibayar -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Domain</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $countBelumTerbayar }}
                                <span class="text-warning text-sm font-weight-bolder">
                                    Item,
                                </span>
                            </h5>
                            <h5 class="font-weight-bolder mb-0">
                                <span class="text-warning text-sm font-weight-bolder">
                                    belum terbayar
                                </span>
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

    <!-- Card untuk BTS yang mendekati jatuh tempo -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">BTS Kontrak</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $countBtsJatuhTempo }}
                                <span class="text-warning text-sm font-weight-bolder">
                                    item, jatuh tempo
                                </span>
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

    <!-- Card untuk Pembayaran yang belum lunas -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Pembayaran</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $countPembayaranBelumLunas }}
                                <span class="text-danger text-sm font-weight-bolder">
                                    item, belum lunas
                                </span>
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

<!-- Upcoming Payment Reminders -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Pengingat Pembayaran Mendatang</h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group">
                    <!-- Dynamically list upcoming payments -->
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center me-3">
                                <i class="ni ni-calendar-grid-58 text-lg opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Motor - Due in 3 days</h6>
                                <span class="text-xs">Rp 5.000.000</span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-link text-danger text-gradient px-3 mb-0">
                                <i class="ni ni-bell-55 me-2"></i> Set Reminder
                            </button>
                        </div>
                    </li>
                    <!-- Add more reminders dynamically -->
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('dashboard')
<script>
    window.onload = function() {
        // Chart for payment trend
        var ctx = document.getElementById("paymentTrendChart").getContext("2d");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Payment Trends",
                    tension: 0.4,
                    borderWidth: 3,
                    borderColor: "#0d6efd",
                    backgroundColor: "rgba(13, 110, 253, 0.1)",
                    data: [1200000, 1300000, 1100000, 1400000, 1500000, 1600000, 1700000, 1200000, 1100000, 1600000, 1400000, 1300000],
                }],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }
</script>
@endpush