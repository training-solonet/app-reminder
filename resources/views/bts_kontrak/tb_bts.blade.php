@extends('layouts.user_type.auth')

@section('content')

@if($bts_expired->count() > 0)
    @foreach($bts_expired as $bts_exp)
        @php
            $days_left = round(Carbon\Carbon::now('Asia/Jakarta')->startOfDay()->diffInDays($bts_exp->jatuh_tempo, false));
        @endphp

        @if($days_left > 0)
            <div class="alert alert-info alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    BTS <strong>{{ $bts_exp->nama_bts }}</strong> akan jatuh tempo dalam <strong>{{ $days_left }}</strong> hari.
                </span>
                <a href="{{ route('transaksi_bts.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
            </div>

        @elseif($days_left == 0)
            <div class="alert alert-warning alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    BTS <strong>{{ $bts_exp->nama_bts }}</strong> jatuh tempo hari ini.
                </span>
                <a href="{{ route('transaksi_bts.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
            </div>

        @else
            <div class="alert alert-danger alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    BTS <strong>{{ $bts_exp->nama_bts }}</strong> sudah melewati jatuh tempo {{ abs($days_left) }} hari yang lalu.
                </span>
                <a href="{{ route('transaksi_bts.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
            </div>
        @endif
    @endforeach
@endif




    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Daftar BTS</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-info btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                            +&nbsp; Tambah
                        </a>
                    </div>
                    <form action="{{ route('bts.index') }}" method="GET" class="d-flex mt-4 p-1">
                        <input type="number" name="tanggal_filter" class="form-control me-2" placeholder="Masukkan Tahun Jatuh Tempo" value="{{ request('tanggal_filter', $tanggalFilter) }}" min="1900" max="3000">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari Nama BTS / Nama User / Status" value="{{ request('search') }}">
                        <button type="submit" class="btn bg-gradient-info mb-0">Filter</button>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Bts</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama User</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Telepon</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun Awal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jatuh Tempo</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal Pertahun</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bts as $key => $item)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $bts->firstItem() + $key }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs text-uppercase font-weight-bold mb-0"><Strong>{{ $item->nama_bts }}</Strong></p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->nama_user }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->telepon }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->tahun_awal }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->jatuh_tempo->format('d M, Y') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Rp{{ number_format($item->nominal_pertahun, 0, ',', '.') }}</p>
                                </td>
                                <td class="text-center">
                                        @if( $item->status == 'Aktif')
                                            <p class="badge badge-sm bg-gradient-success">{{ $item->status }}</p>
                                        @else
                                            <p class="badge badge-sm bg-gradient-danger">{{ $item->status }}</p>
                                        @endif
                                    </td>
                                <td class="text-center">
                                    <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                        <i class="fas fa-eye text-secondary"></i>
                                    </a>
                                    <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" data-bs-original-title="Edit">
                                        <i class="fas fa-pencil-alt text-secondary"></i>
                                    </a>
                                    <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $item->id }});">
                                        <i class="fas fa-trash text-secondary"></i>
                                    </a>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('bts.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                        <div class="d-flex justify-content-center p-2">
                            {{ $bts->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah BTS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('bts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_bts" class="form-label">Nama Bts</label>
                        <input type="text" class="form-control" id="nama_bts" name="nama_bts" placeholder="Masukkan nama BTS" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_user" class="form-label">Nama User</label>
                        <input type="text" class="form-control" id="nama_user" name="nama_user" placeholder="Masukkan nama user" required>
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Masukkan nomor telepon" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_awal" class="form-label">Tahun Awal</label>
                        <input type="number" class="form-control" id="tahun_awal" name="tahun_awal" placeholder="Masukkan tahun awal" required>
                    </div>
                    <div class="mb-3">
                        <label for="jatuh_tempo" class="form-label">Jatuh Tempo</label>
                        <input type="date" class="form-control" id="jatuh_tempo" name="jatuh_tempo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal_pertahun" class="form-label">Nominal Pertahun</label>
                        <input type="number" class="form-control" id="nominal_pertahun" name="nominal_pertahun" placeholder="Masukkan nominal pertahun" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan keterangan" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach ($bts as $item)
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit BTS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('bts.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_bts_{{ $item->id }}" class="form-label">Nama Bts</label>
                        <input type="text" class="form-control" id="nama_bts_{{ $item->id }}" name="nama_bts" value="{{ $item->nama_bts }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_user_{{ $item->id }}" class="form-label">Nama User</label>
                        <input type="text" class="form-control" id="nama_user_{{ $item->id }}" name="nama_user" value="{{ $item->nama_user }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="telepon_{{ $item->id }}" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="telepon_{{ $item->id }}" name="telepon" value="{{ $item->telepon }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_awal_{{ $item->id }}" class="form-label">Tahun Awal</label>
                        <input type="number" class="form-control" id="tahun_awal_{{ $item->id }}" name="tahun_awal" value="{{ $item->tahun_awal }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="jatuh_tempo_{{ $item->id }}" class="form-label">Jatuh Tempo</label>
                        <input type="date" class="form-control" id="jatuh_tempo_{{ $item->id }}" name="jatuh_tempo" value="{{ $item->jatuh_tempo->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal_pertahun_{{ $item->id }}" class="form-label">Nominal Pertahun</label>
                        <input type="number" class="form-control" id="nominal_pertahun_{{ $item->id }}" name="nominal_pertahun" value="{{ floor($item->nominal_pertahun) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan_{{ $item->id }}" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan_{{ $item->id }}" name="keterangan" required>{{ $item->keterangan }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status_{{ $item->id }}" class="form-label">Status</label>
                        <select class="form-control" id="status_{{ $item->id }}" name="status" required>
                            <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ $item->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Update</button>
                </form>
            </div>
        </div>  
    </div>
</div>
@endforeach


@foreach ($bts as $item)
<!-- Modal Detail BTS -->
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail BTS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $fields = [
                        'Nama BTS' => $item->nama_bts,
                        'Nama User' => $item->nama_user,
                        'Telepon' => $item->telepon,
                        'Tahun Awal' => $item->tahun_awal,
                        'Jatuh Tempo' => $item->jatuh_tempo->format('d M, Y'),
                        'Nominal Pertahun' => 'Rp ' . number_format($item->nominal_pertahun, 0, ',', '.'),
                        'Keterangan' => $item->keterangan,
                        'Status' => $item->status ? 'Aktif' : 'Tidak Aktif'
                    ];
                @endphp

                @foreach ($fields as $label => $value)
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">{{ $label }}</div>
                    <div class="col-md-8">{{ $value }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
