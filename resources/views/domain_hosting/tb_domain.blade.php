@extends('layouts.user_type.auth')

@section('content')

@if($domains_expired -> count() > 0)
    @foreach($domains_expired as $domain)

        @php
            $days_left = round(Carbon\Carbon::now()->diffInDays($domain->tgl_expired, false));
        @endphp

        @if($days_left > 0)
            <div class="alert alert-info alert-dismissible fade show mx-4" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Domain <strong>{{ $domain->nama_domain }}</strong> akan expired dalam <strong>{{ $days_left }}</strong> hari.
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        @elseif($days_left == 0)
            <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Domain <strong>{{ $domain->nama_domain }}</strong> expired hari ini.
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        @else
            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Domain <strong>{{ $domain->nama_domain }}</strong> sudah expired {{ abs($days_left) }} hari yang lalu.
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
                                        <p class="text-xs font-weight-bold mb-0">{{ $key + 1 }}</p>
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
                                        <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $domain->id }});">
                                            <i class="fas fa-trash text-secondary"></i>
                                        </a>
                                        <form id="delete-form-{{ $domain->id }}" action="{{ route('domain.destroy', $domain->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center p-2">
                            {{ $domains->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Domain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('domain.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_domain" class="form-label">Nama Domain</label>
                        <input type="text" class="form-control" id="nama_domain" placeholder="Nama Domain" name="nama_domain" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_expired" class="form-label">Tanggal Expired</label>
                        <input type="date" class="form-control" id="tgl_expired" name="tgl_expired" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="nama_perusahaan" placeholder="Nama Perusahaan" name="nama_perusahaan" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal" placeholder="Nominal" name="nominal" required>
                    </div>
                    <div class="mb-3">
                        <label for="status_berlangganan" class="form-label">Status Berlangganan</label>
                        <select class="form-control" id="status_berlangganan" name="status_berlangganan" required>
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
@foreach ($domains as $domain)
<div class="modal fade" id="editModal{{ $domain->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $domain->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $domain->id }}">Edit Domain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('domain.update', $domain->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_domain_{{ $domain->id }}" class="form-label">Nama Domain</label>
                        <input type="text" class="form-control" id="nama_domain_{{ $domain->id }}" name="nama_domain" value="{{ $domain->nama_domain }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_expired_{{ $domain->id }}" class="form-label">Tanggal Expired</label>
                        <input type="date" class="form-control" id="tgl_expired_{{ $domain->id }}" name="tgl_expired" value="{{ $domain->tgl_expired->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_perusahaan_{{ $domain->id }}" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="nama_perusahaan_{{ $domain->id }}" name="nama_perusahaan" value="{{ $domain->nama_perusahaan }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal_{{ $domain->id }}" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal_{{ $domain->id }}" name="nominal" value="{{ $domain->nominal }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status_berlangganan_{{ $domain->id }}" class="form-label">Status Berlangganan</label>
                        <select class="form-control" id="status_berlangganan_{{ $domain->id }}" name="status_berlangganan" required>
                            <option value="Aktif" {{ $domain->status_berlangganan == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ $domain->status_berlangganan == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach


@endsection