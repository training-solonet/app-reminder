@extends('layouts.user_type.auth')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <!-- Header dengan Judul dan Tombol Tambah -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <h5 class="mb-2 mb-md-0">Daftar Pembayaran</h5>
                        <a href="#" class="btn bg-gradient-info btn-sm mt-2 mt-md-0" 
                            type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                            +&nbsp; Tambah
                        </a>
                    </div>

                    <!-- Form Filter -->
                    <form action="{{ route('pembayaran.index') }}" method="GET" 
                          class="row gx-2 gy-2 mt-4 align-items-center">
                        <div class="col-12 col-md-3">
                            <input type="date" name="tanggal_awal" class="form-control" 
                                value="{{ request('tanggal_awal') }}" 
                                placeholder="Tanggal Mulai">
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="date" name="tanggal_akhir" class="form-control" 
                                value="{{ request('tanggal_akhir') }}" 
                                placeholder="Tanggal Akhir">
                        </div>
                        <div class="col-12 col-md-2">
                            <select name="id_jenis_pembayaran" class="form-select">
                                <option value="">Pilih Jenis Pembayaran</option>
                                @foreach ($jenispembayaran as $jenis)
                                    <option value="{{ $jenis->id }}" 
                                        {{ request('id_jenis_pembayaran') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->jenis_pembayaran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="text" name="search" class="form-control" 
                                placeholder="Cari Pengguna / Telepon" 
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-12 col-md-1 d-grid">
                            <button type="submit" class="btn bg-gradient-info">Filter</button>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Pembayaran</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PIC</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Telepon</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>                                
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Bayar</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan Tagihan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Bayar</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bukti</th>
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
                                    <p class="text-xs font-weight-bold mb-0"><strong>{{ $pembayaran->jenisPembayaran->jenis_pembayaran }}</strong></p>
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
                                    <p class="text-xs font-weight-bold mb-0">{{ $pembayaran->created_at->format('d M, Y') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pembayaran->created_at->format('F') }}</p>
                                </td>
                                <td class="text-center">
                                    @if ($pembayaran->status_bayar == 'lunas')
                                        <p class="badge badge-sm bg-gradient-success mb-0">{{ $pembayaran->status_bayar }}</p>
                                    @else
                                        <p class="badge badge-sm bg-gradient-danger mb-0">{{ $pembayaran->status_bayar }}</p>
                                    @endif
                                </td>
                                <td class="text-center">
                                        @if ($pembayaran->bukti)
                                            <a href="javascript:void(0)">
                                                <img src="{{ asset('storage/buktipembayaran/' . $pembayaran->bukti) }}" alt="bukti" class="avatar avatar-sm">
                                            </a>
                                        @else
                                            <img src="https://via.placeholder.com/400x300?text=No+Image" alt="No Image Available" class="avatar avatar-sm">
                                        @endif
                                    </td>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $pembayaran->id }}" data-bs-original-title="Edit">
                                        <i class="fas fa-pencil-alt text-secondary"></i>
                                    </a>
                                    <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $pembayaran->id }});">
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
                <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="pengguna" class="form-label">Pengguna:</label>
                        <input type="text" name="pengguna" id="pengguna" class="form-control" placeholder="Masukkan pengguna" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_telp" class="form-label">No. Telp:</label>
                        <input type="number" name="no_telp" id="no_telp" class="form-control" placeholder="Masukkan nomor telepon">
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_jenis_pembayaran" class="form-label">Jenis Pembayaran:</label>
                        <select name="id_jenis_pembayaran" id="id_jenis_pembayaran" class="form-control" required>
                            <option value="">Pilih Jenis Pembayaran</option>
                            @foreach ($jenispembayaran as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->jenis_pembayaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="keterangan" class="form-label">Keterangan:</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan (opsional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="bukti" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*">
                    </div>
                    <input type="hidden" name="status_bayar" value="lunas">
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
                <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
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
                            <option value="">Pilih Jenis Pembayaran</option>
                            @foreach ($jenispembayaran as $jenis)
                                <option value="{{ $jenis->id }}" {{ $pembayaran->id_jenis_pembayaran == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->jenis_pembayaran }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status_bayar" class="form-label">Status Bayar:</label>
                        <select name="status_bayar" id="edit_status_bayar" class="form-control" required>
                            <option value="lunas" {{ $pembayaran->status_bayar == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum-lunas" {{ $pembayaran->status_bayar == 'belum-lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="keterangan" class="form-label">Keterangan:</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3">{{ $pembayaran->keterangan }}</textarea>
                    </div>
                    <div class="mb-3">
                            <label for="bukti{{ $pembayaran->id }}" class="form-label">Bukti Transaksi</label>
                            <input type="file" class="form-control" id="bukti{{ $pembayaran->id }}" name="bukti">
                            @if($pembayaran->bukti)
                                <img src="{{ asset('storage/buktipembayaran/' . $pembayaran->bukti) }}" class="img-thumbnail mt-2" alt="bukti" style="max-width: 100px;">
                            @endif
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan Perubahan</button>
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
                <img id="imagePreview" src="" alt="Detail Foto" class="img-fluid">
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
