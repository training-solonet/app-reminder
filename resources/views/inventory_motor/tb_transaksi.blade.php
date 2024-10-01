@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <h5 class="mb-0">Transaksi Motor</h5>
                        <a href="#" class="btn bg-gradient-info btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#createModal">
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
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Transaksi</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plat Nomor</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Transaksi</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nota Pajak</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Karyawan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Motor</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $key => $item)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $transaksi->firstItem() + $key }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->jenis_transaksi }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->motor->plat_nomor }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->tanggal_transaksi->format('d M, Y') }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->nota_pajak)
                                            <a href="javascript:void(0)">
                                                <img src="{{ asset('storage/notas/' . $item->nota_pajak) }}" alt="Nota Pajak" class="avatar avatar-sm">
                                            </a>
                                        @else
                                            <p class="text-xs font-weight-bold mb-0">-</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $item->karyawan->nama }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $item->motor->nama_motor }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">Rp{{ number_format($item->nominal, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" data-bs-original-title="Edit">
                                            <i class="fas fa-pencil-alt text-secondary"></i>
                                        </a>
                                        <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $item->id }});">
                                            <i class="fas fa-trash text-secondary"></i>
                                        </a>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('transaksi.destroy', $item->id) }}" method="POST" style="display: none;">
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

<!-- Modal create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                        <select class="form-control" id="jenis_transaksi" name="jenis_transaksi" required>
                            <option value="" disabled selected>Pilih Jenis Transaksi</option>
                            <option value="Pinjam">Pinjam</option>
                            <option value="Bayar Pajak">Bayar Pajak</option>
                            <option value="Servis">Servis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="plat_nomor" class="form-label">Plat Nomor</label>
                        <select class="form-control" id="plat_nomor" name="plat_nomor" required>
                            <option value="" disabled selected>Pilih Motor</option>
                            @foreach($motor as $motors)
                                <option value="{{ $motors->id }}">{{ $motors->plat_nomor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" required>
                    </div>
                    <div class="mb-3">
                        <label for="nota_pajak" class="form-label">Nota Pajak (Gambar)</label>
                        <input type="file" class="form-control" id="nota_pajak" name="nota_pajak">
                    </div>
                    <div class="mb-3">
                        <label for="id_karyawan" class="form-label">Nama Karyawan</label>
                        <select class="form-control" id="id_karyawan" name="id_karyawan" required>
                            <option value="" disabled selected>Pilih Karyawan</option>
                            @foreach($karyawan as $karyawans)
                                <option value="{{ $karyawans->id }}">{{ $karyawans->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_motor" class="form-label">Nama Motor</label>
                        <select class="form-control" id="id_motor" name="id_motor" required>
                            <option value="" disabled selected>Pilih Motor</option>
                            @foreach($motor as $motors)
                                <option value="{{ $motors->id }}">{{ $motors->nama_motor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" placeholder="Masukkan nominal" required>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit -->
@foreach ($transaksi as $item)
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transaksi.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="jenis_transaksi_{{ $item->id }}" class="form-label">Jenis Transaksi</label>
                        <select class="form-control" id="jenis_transaksi_{{ $item->id }}" name="jenis_transaksi" required>
                            <option value="Pinjam" {{ $item->jenis_transaksi == 'Pinjam' ? 'selected' : '' }}>Pinjam</option>
                            <option value="Bayar Pajak" {{ $item->jenis_transaksi == 'Bayar Pajak' ? 'selected' : '' }}>Bayar Pajak</option>
                            <option value="Servis" {{ $item->jenis_transaksi == 'Servis' ? 'selected' : '' }}>Servis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="plat_nomor_{{ $item->id }}" class="form-label">Plat Nomor</label>
                        <select class="form-control" id="plat_nomor_{{ $item->id }}" name="plat_nomor" required>
                            <option value="" disabled selected>Pilih Karyawan</option>
                            @foreach($motor as $motors)
                                <option value="{{ $motors->id }}" {{ $item->id_motor == $motors->id ? 'selected' : '' }}>
                                    {{ $motors->plat_nomor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_transaksi_{{ $item->id }}" class="form-label">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tanggal_transaksi_{{ $item->id }}" name="tanggal_transaksi" value="{{ $item->tanggal_transaksi->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nota_pajak_{{ $item->id }}" class="form-label">Nota Pajak (Gambar)</label>
                        <input type="file" class="form-control" id="nota_pajak" name="nota_pajak">
                        @if ($item->nota_pajak)
                            <a href="{{ $item->nota_pajak }}" target="_blank">
                                <img src="{{ asset('storage/notas/' .$item->nota_pajak) }}" alt="Nota Pajak" class="img-thumbnail mt-2" style="max-width: 100px;">
                            </a>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="id_karyawan_{{ $item->id }}" class="form-label">Nama Karyawan</label>
                        <select class="form-control" id="id_karyawan_{{ $item->id }}" name="id_karyawan" required>
                            <option value="" disabled selected>Pilih Karyawan</option>
                            @foreach($karyawan as $karyawans)
                                <option value="{{ $karyawans->id }}" {{ $item->id_karyawan == $karyawans->id ? 'selected' : '' }}>
                                    {{ $karyawans->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_motor_{{ $item->id }}" class="form-label">Nama Motor</label>
                        <select class="form-control" id="id_motor_{{ $item->id }}" name="id_motor" required>
                            <option value="" disabled selected>Pilih Motor</option>
                            @foreach($motor as $motors)
                                <option value="{{ $motors->id }}" {{ $item->id_motor == $motors->id ? 'selected' : '' }}>
                                    {{ $motors->nama_motor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nominal_{{ $item->id }}" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal_{{ $item->id }}" name="nominal" value="{{ floor($item->nominal) }}" required>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Detail Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imagePreview" src="" alt="Preview Gambar" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const images = document.querySelectorAll("table img.avatar");
        
        images.forEach(image => {
            image.addEventListener("click", function(event) {
                const imgSrc = event.target.getAttribute("src");
                const imagePreview = document.getElementById("imagePreview");
                imagePreview.setAttribute("src", imgSrc);
                const imageModal = new bootstrap.Modal(document.getElementById("imageModal"));
                imageModal.show();
            });
        });
    });
</script>

 
@endsection