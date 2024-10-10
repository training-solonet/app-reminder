@extends('layouts.user_type.auth')

@section('content')
@if($pembayarans_expired->count() > 0)
    @foreach($pembayarans_expired as $pembayaran)

        @php
            $days_left = round(Carbon\Carbon::now('Asia/Jakarta')->startOfDay()->diffInDays($pembayaran->tanggal_jatuh_tempo, false));
        @endphp

        @if($days_left > 0 && $days_left <= 7)
            <div class="alert alert-info alert-dismissible fade show mx-4" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Pembayaran <strong>{{ $pembayaran->jenis_pembayaran }}</strong> akan jatuh tempo dalam <strong>{{ $days_left }}</strong> hari.
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        @elseif($days_left == 0)
            <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Pembayaran <strong>{{ $pembayaran->jenis_pembayaran }}</strong> jatuh tempo hari ini.
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        @elseif($days_left < 0)
            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Pembayaran <strong>{{ $pembayaran->jenis_pembayaran }}</strong> sudah jatuh tempo {{ abs($days_left) }} hari yang lalu.
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                            <h5 class="mb-0">Manajemen Jenis Pembayaran</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-info btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#addJenisPembayaranModal">
                            +&nbsp; Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Pembayaran</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jatuh Tempo</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembayarans as $key => $pembayaran)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $key + 1 }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><strong>{{ $pembayaran->jenis_pembayaran }}</strong></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><strong>{{ $pembayaran->tanggal_jatuh_tempo->format('d M, Y') }}</strong></p>
                                    </td>
                                    <td class="text-center">
                                        @if ($pembayaran->status == 'aktif')
                                            <p class="badge badge-sm bg-gradient-success mb-0">{{ $pembayaran->status }}</p>
                                        @else
                                            <p class="badge badge-sm bg-gradient-danger mb-0">{{ $pembayaran->status }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editJenisPembayaranModal{{ $pembayaran->id }}" data-bs-original-title="Edit">
                                            <i class="fas fa-pencil-alt text-secondary"></i>
                                        </a>
                                        <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $pembayaran->id }});">
                                            <i class="fas fa-trash text-secondary"></i>
                                        </a>
                                        <form id="delete-form-{{ $pembayaran->id }}" action="{{ route('jenis_pembayaran.destroy', $pembayaran->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jenis Pembayaran -->
<div class="modal fade" id="addJenisPembayaranModal" tabindex="-1" aria-labelledby="addJenisPembayaranModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addJenisPembayaranModalLabel">Tambah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('jenis_pembayaran.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
                        <input type="text" class="form-control" id="jenis_pembayaran" placeholder="Jenis Pembayaran" name="jenis_pembayaran" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="tidak-aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit Jenis Pembayaran -->
@foreach ($pembayarans as $pembayaran)
<div class="modal fade" id="editJenisPembayaranModal{{ $pembayaran->id }}" tabindex="-1" aria-labelledby="editJenisPembayaranModalLabel{{ $pembayaran->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJenisPembayaranModalLabel{{ $pembayaran->id }}">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('jenis_pembayaran.update', $pembayaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="jenis_pembayaran_{{ $pembayaran->id }}" class="form-label">Jenis Pembayaran</label>
                        <input type="text" class="form-control" id="jenis_pembayaran_{{ $pembayaran->id }}" name="jenis_pembayaran" value="{{ $pembayaran->jenis_pembayaran }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status_{{ $pembayaran->id }}" class="form-label">Status</label>
                        <select id="status_{{ $pembayaran->id }}" name="status" class="form-control" required>
                            <option value="aktif" {{ $pembayaran->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak-aktif" {{ $pembayaran->status == 'tidak-aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_jatuh_tempo_{{ $pembayaran->id }}" class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" class="form-control" id="tanggal_jatuh_tempo_{{ $pembayaran->id }}" name="tanggal_jatuh_tempo" value="{{ $pembayaran->tanggal_jatuh_tempo }}" required>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection