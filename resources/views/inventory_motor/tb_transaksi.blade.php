@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <h5 class="mb-0">Transaksi Motor</h5>
                        <a href="#" class="btn bg-gradient-info btn-sm mb-2 mb-md-0 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#createModal">
                            +&nbsp; Tambah
                        </a>
                    </div>
                    
                    <form action="{{ route('transaksi.index') }}" method="GET" class="d-flex flex-column flex-md-row mt-4 p-1">
                        <input type="date" name="start_date" class="form-control mb-2 mb-md-0 me-md-2" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" class="form-control mb-2 mb-md-0 me-md-2" value="{{ request('end_date') }}">
                        <input type="text" name="search" class="form-control mb-2 mb-md-0 me-md-2" placeholder="Cari" value="{{ request('search') }}">
                        <button type="submit" class="btn bg-gradient-info mb-0">Filter</button>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Transaksi</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Motor</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plat Nomor</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Transaksi</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>                                   
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nota Pajak</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PIC</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $key => $item)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $key + 1 }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->jenis_transaksi }}</p>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $item->motor->nama_motor }}</span>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->motor->plat_nomor }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->created_at->format('d M, Y') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">Rp{{ number_format($item->nominal, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->nota_pajak)
                                            <a href="javascript:void(0)">
                                                <img src="{{ asset('storage/notas/' . $item->nota_pajak) }}" alt="Nota Pajak" class="avatar avatar-sm">
                                            </a>
                                        @else
                                            <img src="https://via.placeholder.com/400x300?text=No+Image" alt="No Image Available" class="avatar avatar-sm">
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $item->karyawan->nama }}</span>
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
                        <label for="nama_motor" class="form-label">Nama Motor</label>
                        <select class="form-control" id="nama_motor" name="nama_motor" required>
                            <option value="" disabled selected>Pilih Motor</option>
                            @foreach($motor->unique('nama_motor') as $motors)
                                <option value="{{ $motors->nama_motor }}">
                                    {{ $motors->nama_motor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="plat_nomor" class="form-label">Plat Nomor</label>
                        <select class="form-control" id="plat_nomor" name="plat_nomor" required>
                            <option value="" disabled selected>Pilih Plat Motor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_karyawan" class="form-label">PIC</label>
                        <select class="form-control" id="id_karyawan" name="id_karyawan" required>
                            <option value="" disabled selected>Pilih PIC</option>
                            @foreach($karyawan as $karyawans)
                                <option value="{{ $karyawans->id }}">{{ $karyawans->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" placeholder="Masukkan nominal" required>
                    </div>
                    <div class="mb-3">
                        <label for="nota_pajak" class="form-label">Nota Pajak (Gambar)</label>
                        <input type="file" class="form-control" id="nota_pajak" name="nota_pajak">
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const namaMotorSelect = document.getElementById('nama_motor');
        const platNomorSelect = document.getElementById('plat_nomor');

        namaMotorSelect.addEventListener('change', function () {
            const selectedMotor = this.value;

            platNomorSelect.innerHTML = '<option value="" disabled selected>Pilih Plat Motor</option>';

            @json($motor).forEach(function(motor) {
                if (motor.nama_motor === selectedMotor) {
                    const option = document.createElement('option');
                    option.value = motor.id;
                    option.textContent = motor.plat_nomor;
                    platNomorSelect.appendChild(option);
                }
            });
        });
    });
</script>


<!-- Modal edit -->
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
                        <label for="edit_jenis_transaksi{{ $item->id }}" class="form-label">Jenis Transaksi</label>
                        <select class="form-control" id="edit_jenis_transaksi{{ $item->id }}" name="jenis_transaksi" required>
                            <option value="Pinjam" {{ $item->jenis_transaksi == 'Pinjam' ? 'selected' : '' }}>Pinjam</option>
                            <option value="Bayar Pajak" {{ $item->jenis_transaksi == 'Bayar Pajak' ? 'selected' : '' }}>Bayar Pajak</option>
                            <option value="Servis" {{ $item->jenis_transaksi == 'Servis' ? 'selected' : '' }}>Servis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_motor{{ $item->id }}" class="form-label">Nama Motor</label>
                        <select class="form-control" id="edit_nama_motor{{ $item->id }}" name="nama_motor" required>
                            <option value="" disabled>Pilih Motor</option>
                            @foreach($motor->unique('nama_motor') as $motors)
                                <option value="{{ $motors->nama_motor }}" {{ $motors->nama_motor == $item->nama_motor ? 'selected' : '' }}>
                                    {{ $motors->nama_motor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_plat_nomor{{ $item->id }}" class="form-label">Plat Nomor</label>
                        <select class="form-control" id="edit_plat_nomor{{ $item->id }}" name="plat_nomor" required>
                            <option value="" disabled>Pilih Plat Motor</option>
                            @foreach($motor as $motors)
                                @if($motors->nama_motor == $item->nama_motor)
                                    <option value="{{ $motors->id }}" {{ $motors->id == $item->id_motor ? 'selected' : '' }}>
                                        {{ $motors->plat_nomor }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_karyawan{{ $item->id }}" class="form-label">PIC</label>
                        <select class="form-control" id="edit_id_karyawan{{ $item->id }}" name="id_karyawan" required>
                            <option value="" disabled>Pilih PIC</option>
                            @foreach($karyawan as $karyawans)
                                <option value="{{ $karyawans->id }}" {{ $karyawans->id == $item->id_karyawan ? 'selected' : '' }}>
                                    {{ $karyawans->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nominal{{ $item->id }}" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="edit_nominal{{ $item->id }}" name="nominal" value="{{ $item->nominal }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nota_pajak{{ $item->id }}" class="form-label">Nota Pajak (Gambar)</label>
                        <input type="file" class="form-control" id="edit_nota_pajak{{ $item->id }}" name="nota_pajak">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti nota.</small>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const namaMotorSelect = document.getElementById('edit_nama_motor');
        const platNomorSelect = document.getElementById('edit_plat_nomor');

        namaMotorSelect.addEventListener('change', function () {
            const selectedMotor = this.value;

            platNomorSelect.innerHTML = '<option value="" disabled selected>Pilih Plat Motor</option>';

            @json($motor).forEach(function(motor) {
                if (motor.nama_motor === selectedMotor) {
                    const option = document.createElement('option');
                    option.value = motor.id;
                    option.textContent = motor.plat_nomor;
                    platNomorSelect.appendChild(option);
                }
            });
        });
    });
</script>


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