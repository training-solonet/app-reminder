@extends('layouts.user_type.auth')

@section('content')

<div class="alert alert-info mx-4" role="alert">
    <span class="text-white">
        <strong>Perhatian!</strong> 
             BTS Bts Solo akan expired dalam 3 hari.
    </span>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Tabel Bts</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-info btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                            +&nbsp; Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Bts</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama User</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Telepon</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun Awal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jatuh Tempo</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal Pertahun</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
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
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->nama_bts }}</p>
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
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->keterangan }}</p>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" data-bs-original-title="Edit">
                                        <i class="fas fa-user-edit text-secondary"></i>
                                    </a>
                                    <i class="cursor-pointer fas fa-trash text-secondary" data-bs-original-title="Delete"></i>
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

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Bts</h5>
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
                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Bts</h5>
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
                    <button type="submit" class="btn bg-gradient-info">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach


@endsection
