@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Karyawan</h5>
                        
                        <div class="d-flex">
                            <form action="{{ route('karyawan.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Cari Karyawan..." value="{{ request('search') }}">
                                <button type="submit" class="btn bg-gradient-info btn-sm ms-3">Cari</button>
                            </form>
                            
                            <a href="#" class="btn bg-gradient-info btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#createModal">
                                +&nbsp; Tambah
                            </a>
                        </div>
                    </div>
                </div>
                

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Karyawan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Foto Karyawan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Handphone</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Divisi</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jabatan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Cuti</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Karyawan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $key => $item)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $karyawan->firstItem() + $key }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><strong>{{ $item->nama }}</strong></p>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->foto_karyawan)
                                            <a href="javascript:void(0)">
                                                <img src="{{ asset('storage/karyawan/' . $item->foto_karyawan) }}" alt="foto karyawan" class="avatar avatar-sm">
                                            </a>
                                        @else
                                            <p class="text-xs font-weight-bold mb-0">No Image</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->no_hp }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->divisi }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->jabatan }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->status_cuti ? 'Cuti' : 'Tidak cuti'}}</p>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status_karyawan == 'aktif')
                                            <p class="text-uppercase badge badge-sm bg-gradient-success mb-0">{{ $item->status_karyawan }}</p>
                                        @else
                                            <p class="text-uppercase badge badge-sm bg-gradient-danger mb-0">{{ $item->status_karyawan }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                            <i class="fas fa-eye text-secondary"></i>
                                        </a>
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                            <i class="fas fa-pencil-alt text-secondary"></i>
                                        </a>
                                        <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $item->id }});">
                                            <i class="fas fa-trash text-secondary"></i>
                                        </a>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('karyawan.destroy', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center p-2">
                            {{ $karyawan->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($karyawan as $item)
<!-- Modal Detail Karyawan -->
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4 p-3">
                    <div class="col text-center">
                        @if ($item->foto_karyawan)
                            <img src="{{ asset('storage/karyawan/' . $item->foto_karyawan) }}" alt="Foto {{ $item->nama }}" class="img-fluid rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <p class="text-muted">No Image</p>
                        @endif
                    </div>
                </div>

                @php
                    $fields = [
                        'Nama' => $item->nama,
                        'NIK' => $item->nik,
                        'Jenis Kelamin' => $item->jenis_kelamin,
                        'Tanggal Masuk' => $item->tgl_masuk->format('d M, Y'),
                        'Tanggal Lahir' => $item->tgl_lahir->format('d M, Y'),
                        'Tempat Lahir' => $item->tempat_lahir,
                        'No HP' => $item->no_hp,
                        'Agama' => $item->agama,
                        'Divisi' => $item->divisi,
                        'Jabatan' => $item->jabatan,
                        'Alamat' => $item->alamat,
                        'Status Cuti' => $item->status_cuti ? 'Cuti' : 'Tidak cuti',
                        'Status Karyawan' => $item->status_karyawan
                    ];
                @endphp

                @foreach ($fields as $label => $value)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">{{ $label }}</div>
                    <div class="col-md-8">{{ $value }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="number" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan tempat lahir" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan no HP" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="agama" class="form-label">Agama</label>
                            <input type="text" class="form-control" id="agama" name="agama" placeholder="Masukkan agama" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="divisi" class="form-label">Divisi</label>
                            <input type="text" class="form-control" id="divisi" name="divisi" placeholder="Masukkan divisi" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukkan jabatan" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_karyawan" class="form-label">Status Karyawan</label>
                            <select class="form-control" id="status_karyawan" name="status_karyawan" required>
                                <option value="" disabled selected>Pilih Status Karyawan</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak-aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Foto Karyawan</label>
                            <input type="file" class="form-control" id="foto_karyawan" name="foto_karyawan" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_cuti" class="form-label">Status Cuti</label>
                            <select class="form-control" id="status_cuti" name="status_cuti" required>
                                <option value="" disabled selected>Pilih Status Cuti</option>
                                <option value="1">Cuti</option>
                                <option value="0">Tidak Cuti</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Karyawan -->
@foreach ($karyawan as $item)
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKaryawanModalLabel">Edit Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('karyawan.update', $item->id) }}" method="POST" enctype="multipart/form-data">  
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $item->nama }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="number" class="form-control" id="nik" name="nik" value="{{ $item->nik }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" disabled>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ $item->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ $item->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="{{ $item->tgl_masuk->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="{{ $item->tgl_lahir->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ $item->tempat_lahir }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $item->no_hp }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="agama" class="form-label">Agama</label>
                            <input type="text" class="form-control" id="agama" name="agama" value="{{ $item->agama }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="divisi" class="form-label">Divisi</label>
                            <input type="text" class="form-control" id="divisi" name="divisi" value="{{ $item->divisi }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ $item->jabatan }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" required>{{ $item->alamat }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_karyawan" class="form-label">Status Karyawan</label>
                            <select class="form-control" id="status_karyawan" name="status_karyawan" required>
                                <option value="" disabled>Pilih Status Karyawan</option>
                                <option value="aktif" {{ $item->status_karyawan == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak-aktif" {{ $item->status_karyawan == 'tidak-aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Foto Karyawan</label>
                            <input type="file" class="form-control" id="foto_karyawan" name="foto_karyawan" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_cuti" class="form-label">Status Cuti</label>
                            <select class="form-control" id="status_cuti" name="status_cuti" required>
                                <option value="" disabled>Pilih Status Cuti</option>
                                <option value="1" {{ $item->status_cuti == 1 ? 'selected' : '' }}>Cuti</option>
                                <option value="0" {{ $item->status_cuti == 0 ? 'selected' : '' }}>Tidak Cuti</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-gradient-info">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- End of Modal Edit Karyawan -->

                                    
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
