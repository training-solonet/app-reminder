@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <!-- Card for Domains that haven't been paid -->
    <div class="col-xl-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('domain.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Domain</p>
                            <h4 class="font-weight-bolder mb-0 text-dark">
                                {{ $countBelumTerbayar }}
                                <span class="text-danger text-md font-weight-bolder">Item,</span>
                            </h4>
                            <h4 class="font-weight-bolder mb-0">
                                <span class="text-info text-md font-weight-bolder">belum terbayar</span>
                            </h4>
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

    <!-- Card for Motorcycle Taxes nearing deadline -->
    <div class="col-xl-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('motor.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Motor Pajak</p>
                            <h4 class="font-weight-bolder mb-0 text-dark">
                                {{ $countMotorPajakJatuhTempo }}
                                <span class="text-danger text-md font-weight-bolder">item, jatuh tempo</span>
                            </h4>
                            <h4 class="font-weight-bolder mb-0">
                                <span class="text-info text-md font-weight-bolder">bayar segera</span>
                            </h4>
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

    <!-- Card for BTS nearing contract deadline -->
    <div class="col-xl-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('bts.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">BTS Kontrak</p>
                            <h4 class="font-weight-bolder mb-0 text-dark">
                                {{ $countBtsJatuhTempo }}
                                <span class="text-danger text-md font-weight-bolder">item, jatuh tempo</span>
                            </h4>
                            <h4 class="font-weight-bolder mb-0">
                                <span class="text-info text-md font-weight-bolder">segera diperbarui</span>
                            </h4>
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

    <!-- Card for Active Reminders -->
    <div class="col-xl-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('reminders.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Reminders Aktif</p>
                            <h4 class="font-weight-bolder mb-0 text-dark">
                                {{ $activeReminders }}
                                <span class="text-danger text-md font-weight-bolder">item aktif,</span>
                            </h4>
                            <h4 class="font-weight-bolder mb-0">
                                <span class="text-info text-md font-weight-bolder">dijalankan segera</span>
                            </h4>
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

    <!-- Card for Pending Reminders -->
    <div class="col-xl-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('reminders.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Reminders Belum Dilaksanakan</p>
                            <h4 class="font-weight-bolder mb-0 text-dark">
                                {{ $pendingReminders }}
                                <span class="text-danger text-md font-weight-bolder">item pending,</span>
                            </h4>
                            <h4 class="font-weight-bolder mb-0">
                                <span class="text-info text-md font-weight-bolder">belum selesai</span>
                            </h4>
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

    <!-- Card for Unpaid Payments -->
    <div class="col-xl-6 col-sm-12 mb-4">
        <div class="card" style="height: 100%; padding: 1.5rem;">
            <a href="{{ route('pembayaran.index') }}" class="stretched-link"></a>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-lg mb-0 text-capitalize font-weight-bold text-dark">Pembayaran</p>
                            <h4 class="font-weight-bolder mb-0 text-dark">
                                {{ $countPembayaranBelumLunas }}
                                <span class="text-danger text-md font-weight-bolder">item, belum lunas</span>
                            </h4>
                            <h4 class="font-weight-bolder mb-0">
                                <span class="text-info text-md font-weight-bolder">segera dilunasi</span>
                            </h4>
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
