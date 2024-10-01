@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Transaksi Domain</h5>
                        </div>
                        <button class="btn bg-gradient-info btn-sm" data-bs-toggle="modal" data-bs-target="#addTransaksiModal">
                            +&nbsp; Tambah
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Domain</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Transaksi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bukti</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $key => $trans)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $key + 1 }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $trans->domain->nama_domain }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($trans->nominal, 0, ',', '.') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $trans->status }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $trans->tgl_transaksi->format('d M, Y') }}</p>
                                </td>
                                <td class="text-center">
                                    @if($trans->bukti)
                                        <img src="{{ asset('storage/buktidomain/' . $trans->bukti) }}" alt="bukti" class="img-thumbnail" style="width: 100px;">
                                    @else
                                        <p class="text-xs font-weight-bold mb-0">Tidak ada foto</p>
                                    @endif
                                </td>
                                </td>
                                <td class="text-center">
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $trans->id }}" data-bs-original-title="Edit">
                                            <i class="fas fa-pencil-alt text-secondary"></i>
                                        </a>
                                        <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $trans->id }});">
                                            <i class="fas fa-trash text-secondary"></i>
                                        </a>
                                        <form id="delete-form-{{ $trans->id }}" action="{{ route('domain.destroy', $trans->id) }}" method="POST" style="display: none;">
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

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="addTransaksiModal" tabindex="-1" aria-labelledby="addTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransaksiModalLabel">Tambah Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transaksi_domain.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="domain_id" class="form-label">Nama Domain</label>
                        <select class="form-control" id="domain_id" name="domain_id" required>
                            <option value="" disabled selected>Pilih Domain</option>
                            @foreach($domain as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_domain }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_transaksi" class="form-label">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal" placeholder="Nominal" name="nominal" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="lunas">Lunas</option>
                            <option value="belum-lunas">Belum Lunas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bukti" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*">
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Transaksi -->
@foreach($transaksis as $trans)
<div class="modal fade" id="editModal{{ $trans->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $trans->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $trans->id }}">Edit Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transaksi_domain.update', $trans->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_domain{{ $trans->id }}" class="form-label">Nama Domain</label>
                        <input type="text" class="form-control" id="nama_domain{{ $trans->id }}" name="nama_domain" value="{{ $trans->domain->nama_domain }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_transaksi{{ $trans->id }}" class="form-label">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tgl_transaksi{{ $trans->id }}" name="tgl_transaksi" value="{{ $trans->tgl_transaksi }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal{{ $trans->id }}" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal{{ $trans->id }}" name="nominal" value="{{ $trans->nominal }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status{{ $trans->id }}" class="form-label">Status</label>
                        <select class="form-control" id="status{{ $trans->id }}" name="status" required>
                            <option value="lunas" {{ $trans->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum-lunas" {{ $trans->status == 'belum-lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bukti{{ $trans->id }}" class="form-label">Bukti Pembayaran</label>
                        @if($trans->bukti)
                            <img src="{{ asset('storage/buktidomain/' . $trans->bukti) }}" alt="bukti" class="img-thumbnail mb-3" style="width: 100px;">
                        @endif
                        <input type="file" class="form-control" id="bukti{{ $trans->id }}" name="bukti" accept="image/*">
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection