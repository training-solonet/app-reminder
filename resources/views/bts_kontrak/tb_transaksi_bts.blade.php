@extends('layouts.user_type.auth')

@section('content')   

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Tabel Transaksi Bts</h5>
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
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Bts</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Transaksi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bukti</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi_bts as $key => $item)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $transaksi_bts->firstItem() + $key }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->bts->nama_bts }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->tgl_transaksi->format('d M, Y') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">tidak ada foto</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $item->status }}</p>
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
                <h5 class="modal-title" id="createModalLabel">Tambah Transaksi Bts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transaksi_bts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="bts_id" class="form-label">Nama Bts</label>
                        <select class="form-control" id="bts_id" name="bts_id" required>
                            <option value="" disabled selected>Pilih Bts</option>
                            @foreach($bts as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_bts }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_transaksi" class="form-label">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" placeholder="Masukkan nominal transaksi" required>
                    </div>
                    <div class="mb-3">
                        <label for="bukti" class="form-label">Bukti</label>
                        <input type="file" class="form-control" id="bukti" name="bukti">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="lunas">Lunas</option>
                            <option value="belum_lunas">Belum Lunas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


@foreach ($transaksi_bts as $item)
    <!-- Modal Edit -->
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Transaksi Bts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('transaksi_bts.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="bts_id{{ $item->id }}" class="form-label">Nama Bts</label>
                            <select class="form-control" id="bts_id{{ $item->id }}" name="bts_id" required>
                                @foreach($bts as $b)
                                    <option value="{{ $b->id }}" {{ $b->id == $item->bts_id ? 'selected' : '' }}>{{ $b->nama_bts }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tgl_transaksi{{ $item->id }}" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" id="tgl_transaksi{{ $item->id }}" name="tgl_transaksi" value="{{ $item->tgl_transaksi->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                        <label for="nominal{{ $item->id }}" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal{{ $item->id }}" name="nominal" value="{{ floor($item->nominal) }}" required>
                    </div>
                        <div class="mb-3">
                            <label for="bukti{{ $item->id }}" class="form-label">Bukti</label>
                            <input type="file" class="form-control" id="bukti{{ $item->id }}" name="bukti">
                            @if($item->bukti)
                            <img src="{{ asset('storage/bukti_transaksi/' . $item->bukti) }}" class="img-thumbnail mt-2" alt="Bukti Transaksi" style="max-width: 100px;">
                        @endif
                        </div>
                        <div class="mb-3">
                            <label for="status{{ $item->id }}" class="form-label">Status</label>
                            <select class="form-control" id="status{{ $item->id }}" name="status" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="lunas" {{ $item->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="belum_lunas" {{ $item->status == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            </select>
                        </div>
                        <button type="submit" class="btn bg-gradient-info">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach


@endsection
