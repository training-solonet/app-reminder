@extends('layouts.user_type.auth')

@section('content')

@if($domains_expired->count() > 0)
    @foreach($domains_expired as $domain)
        @php
            $days_left = round(Carbon\Carbon::now('Asia/Jakarta')->startOfDay()->diffInDays($domain->tgl_expired, false));
        @endphp

        @if($days_left > 0 && $days_left <= 7)
            <div class="alert alert-info alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Domain <strong>{{ $domain->nama_domain }}</strong> akan expired dalam <strong>{{ $days_left }}</strong> hari.
                </span>
                <a href="{{ route('transaksi_domain.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
            </div>

        @elseif($days_left == 0)
            <div class="alert alert-warning alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Domain <strong>{{ $domain->nama_domain }}</strong> expired hari ini.
                </span>
                <a href="{{ route('transaksi_domain.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
            </div>

        @elseif($days_left < 0)
            <div class="alert alert-danger alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Domain <strong>{{ $domain->nama_domain }}</strong> sudah expired {{ abs($days_left) }} hari yang lalu.
                </span>
                <a href="{{ route('transaksi_domain.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
            </div>
        @endif
    @endforeach
@endif

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Daftar Domain</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-info btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                            +&nbsp; Tambah
                        </a>
                    </div>
                    <form action="{{ route('domain.index') }}" method="GET" class="d-flex mt-4 p-1">
                        <input type="number" name="tanggal_filter" class="form-control me-2" placeholder="Masukkan Tahun" value="{{ request('tanggal_filter', $tanggalFilter) }}" min="1900" max="3000">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari Nama Domain / Status Berlangganan" value="{{ request('search') }}">
                        <button type="submit" class="btn bg-gradient-info mb-0">Filter</button>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Domain</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Perusahaan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Expired</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Berlangganan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($domains as $key => $domain)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $domains->firstItem() + $key }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><strong>{{ $domain->nama_domain }}</strong></p>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $domain->nama_perusahaan }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">Rp {{ number_format($domain->nominal, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $domain->tgl_expired->format('d M, Y') }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if ($domain->status_berlangganan == 'Aktif')
                                            <p class="badge badge-sm bg-gradient-success mb-0">{{ $domain->status_berlangganan }}</p>
                                        @else
                                            <p class="badge badge-sm bg-gradient-danger mb-0">{{ $domain->status_berlangganan }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $domain->id }}" data-bs-original-title="Edit">
                                            <i class="fas fa-pencil-alt text-secondary"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 mx-3">
                        {{ $domains->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
