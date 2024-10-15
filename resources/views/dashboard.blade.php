@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <!-- Domain Card -->
    <div class="col-xl-6 col-md-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem; position: relative;">
            <a href="{{ route('domain.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Domain</p>
                            <h3 class="font-weight-bolder mb-0 text-dark">
                                {{ $countBelumTerbayar }}
                            </h3>
                            <button class="btn btn-icon btn-2 bg-gradient-info mt-1" type="button">
                                <span class="btn-inner--text">Belum Terbayar</span>
                                &nbsp;
                                <span class="btn-inner--icon">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md" style="font-size: 2.5rem;">
                            <i class="ni ni-money-coins text-xl opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Motorcycle Tax Card -->
    <div class="col-xl-6 col-md-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('motor.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Pajak Motor</p>
                            <h3 class="font-weight-bolder mb-0 text-dark">
                                {{ $countMotorPajakJatuhTempo }}
                            </h3>
                            <button class="btn btn-icon btn-2 bg-gradient-success mt-1" type="button">
                                <span class="btn-inner--text">Jatuh Tempo</span>
                                &nbsp;
                                <span class="btn-inner--icon">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md" style="font-size: 2.5rem;">
                            <i class="ni ni-delivery-fast text-xl opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BTS Contract Card -->
    <div class="col-xl-6 col-md-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('bts.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">BTS Kontrak</p>
                            <h3   class="font-weight-bolder mb-0 text-dark">
                                {{ $countBtsJatuhTempo }}
                            </h3>
                            <button class="btn btn-icon btn-2 bg-gradient-warning mt-1" type="button">
                                <span class="btn-inner--text">Jatuh Tempo</span>
                                &nbsp;
                                <span class="btn-inner--icon">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md" style="font-size: 2.5rem;">
                            <i class="ni ni-building text-xl opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Reminders Card -->
    <div class="col-xl-6 col-md-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('reminders.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Reminder Aktif</p>
                            <h3 class="font-weight-bolder mb-0 text-dark">
                                {{ $activeReminders }}
                            </h3>
                            <button class="btn btn-icon btn-2 bg-gradient-primary mt-1" type="button">
                                <span class="btn-inner--text">Segera Lakukan</span>
                                &nbsp;
                                <span class="btn-inner--icon">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md" style="font-size: 2.5rem;">
                            <i class="ni ni-bell-55 text-xl opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Reminders Card -->
    <div class="col-xl-6 col-md-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('reminders.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Reminder Belum Dilaksanakan</p>
                            <h3 class="font-weight-bolder mb-0 text-dark">
                                {{ $pendingReminders }}
                            </h3>
                            <button class="btn btn-icon btn-2 bg-gradient-warning mt-1" type="button">
                                <span class="btn-inner--text">Belum Selesai</span>
                                &nbsp;
                                <span class="btn-inner--icon">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md" style="font-size: 2.5rem;">
                            <i class="ni ni-time-alarm text-xl opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unpaid Payments Card -->
    <div class="col-xl-6 col-md-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('pembayaran.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Pembayaran</p>
                            <h3 class="font-weight-bolder mb-0 text-dark">
                                {{ $countPembayaranBelumLunas }}
                            </h3>
                            <button class="btn btn-icon btn-2 bg-gradient-danger mt-1" type="button">
                                <span class="btn-inner--text">Bayar Sekarang</span>
                                &nbsp;
                                <span class="btn-inner--icon">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md" style="font-size: 2.5rem;">
                            <i class="ni ni-credit-card text-xl opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
