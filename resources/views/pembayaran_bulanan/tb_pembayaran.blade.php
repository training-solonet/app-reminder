@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Daftar Pembayaran</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-info btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                            +&nbsp; Tambah Pembayaran
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengguna</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Telepon</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Pembayaran</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Bayar</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Bayar</th>
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
                                    <p class="text-xs font-weight-bold mb-0">{{ $pembayaran->pengguna }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pembayaran->no_telp }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pembayaran->keterangan ?? '-' }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pembayaran->jenisPembayaran->jenis_pembayaran }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pembayaran->tgl_bayar->format('d M, Y') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pembayaran->status_bayar }}</p>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $pembayaran->id }}" data-bs-original-title="Edit">
                                        <i class="fas fa-pencil-alt text-secondary"></i>
                                    </a>
                                    <a href="{{ route('pembayaran.destroy', $pembayaran->id) }}" class="p-1" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $pembayaran->id }}').submit();">
                                        <i class="fas fa-trash text-secondary"></i>
                                    </a>
                                    <form id="delete-form-{{ $pembayaran->id }}" action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" style="display: none;">
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

 <!-- Modal Tambah Pembayaran -->
 <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="addJenisPembayaranModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addJenisPembayaranModalLabel">Tambah Pembayaran</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('pembayaran.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="pengguna" class="form-label">Pengguna:</label>
                        <input type="text" name="pengguna" id="pengguna" class="form-control" placeholder="Masukkan pengguna" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_telp" class="form-label">No. Telp:</label>
                        <input type="number" name="no_telp" id="no_telp" class="form-control" placeholder="Masukkan nomor telepon" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_jenis_pembayaran" class="form-label">Jenis Pembayaran:</label>
                        <select name="id_jenis_pembayaran" id="id_jenis_pembayaran" class="form-control" required>
                            <option value="">-- Pilih Jenis Pembayaran --</option>
                            @foreach ($jenispembayaran as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->jenis_pembayaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tgl_bayar" class="form-label">Tanggal Bayar:</label>
                        <input type="date" name="tgl_bayar" id="tgl_bayar" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status_bayar" class="form-label">Status Bayar:</label>
                        <select name="status_bayar" id="status_bayar" class="form-control" required>
                            <option value="lunas">Lunas</option>
                            <option value="belum-lunas">Belum Lunas</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="bulan_bayar" class="form-label">Bulan Bayar:</label>
                        <input type="month" name="bulan_bayar" id="bulan_bayar" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="keterangan" class="form-label">Keterangan:</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan (opsional)"></textarea>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit -->
@foreach ($pembayarans as $pembayaran)
<div class="modal fade" id="editModal{{ $pembayaran->id }}" tabindex="-1" role="dialog" aria-labelledby="editJenisPembayaranModalLabel{{ $pembayaran->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editJenisPembayaranModalLabel{{ $pembayaran->id }}">Edit Pembayaran</h4>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group mb-3">
                                                            <label for="pengguna" class="form-label">Pengguna:</label>
                                                            <input type="text" name="pengguna" id="edit_pengguna" class="form-control" value="{{ $pembayaran->pengguna }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="no_telp" class="form-label">No. Telp:</label>
                                                            <input type="number" name="no_telp" id="edit_no_telp" class="form-control" value="{{ $pembayaran->no_telp }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="id_jenis_pembayaran" class="form-label">Jenis Pembayaran:</label>
                                                            <select name="id_jenis_pembayaran" id="edit_id_jenis_pembayaran" class="form-control" required>
                                                                <option value="">-- Pilih Jenis Pembayaran --</option>
                                                                @foreach ($jenispembayaran as $jenis)
                                                                    <option value="{{ $jenis->id }}" {{ $pembayaran->id_jenis_pembayaran == $jenis->id ? 'selected' : '' }}>
                                                                        {{ $jenis->jenis_pembayaran }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="tgl_bayar" class="form-label">Tanggal Bayar:</label>
                                                            <input type="date" name="tgl_bayar" id="edit_tgl_bayar" class="form-control" value="{{ $pembayaran->tgl_bayar }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="status_bayar" class="form-label">Status Bayar:</label>
                                                            <select name="status_bayar" id="edit_status_bayar" class="form-control" required>
                                                                <option value="lunas" {{ $pembayaran->status_bayar == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                                                <option value="belum-lunas" {{ $pembayaran->status_bayar == 'belum-lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="bulan_bayar" class="form-label">Bulan Bayar:</label>
                                                            <input type="month" name="bulan_bayar" id="edit_bulan_bayar" class="form-control" value="{{ $pembayaran->bulan_bayar }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="keterangan" class="form-label">Keterangan:</label>
                                                            <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3">{{ $pembayaran->keterangan }}</textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
@endforeach

@endsection
