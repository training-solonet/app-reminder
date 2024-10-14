@extends('layouts.user_type.auth')

@section('content')

@if($motors_expiring->count() > 0)
    @foreach($motors_expiring as $motor_exp)

        @php
            $days_left = round(Carbon\Carbon::now('Asia/Jakarta')->startOfDay()->diffInDays($motor_exp->tanggal_pajak, false));
        @endphp

        @if($days_left > 0)
            <div class="alert alert-info alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Pajak motor <strong>{{ $motor_exp->nama_motor }}</strong> dengan plat <strong>{{ $motor_exp->plat_nomor }}</strong> akan jatuh tempo dalam <strong>{{ $days_left }}</strong> hari.
                </span>
                <a href="{{ route('transaksi.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
            </div>

        @elseif($days_left == 0)
            <div class="alert alert-warning alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Pajak motor <strong>{{ $motor_exp->nama_motor }}</strong> dengan plat <strong>{{ $motor_exp->plat_nomor }}</strong> akan jatuh tempo hari ini.
                </span>
                <a href="{{ route('transaksi.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
            </div>

        @else
            <div class="alert alert-danger alert-dismissible fade show mx-4 d-flex justify-content-between align-items-center" role="alert">
                <span class="text-white">
                    <strong>Perhatian!</strong> 
                    Pajak motor <strong>{{ $motor_exp->nama_motor }}</strong> dengan plat <strong>{{ $motor_exp->plat_nomor }}</strong> sudah jatuh tempo {{ abs($days_left) }} hari yang lalu.
                </span>
                <a href="{{ route('transaksi.index') }}" class="text-white" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 8a.5.5 0 0 1 .5-.5h11.293L8.354 4.354a.5.5 0 1 1 .708-.708l4.5 4.5a.5.5 0 0 1 0 .708l-4.5 4.5a.5.5 0 0 1-.708-.708L13.293 8.5H2a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </a>
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
                            <h5 class="mb-0">Daftar Motor</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-info btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                            +&nbsp; Tambah
                        </a>
                    </div>

                    <form method="GET" action="{{ route('motor.index') }}" class="d-flex mt-4 p-1">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama atau plat nomor..." value="{{ request('search') }}">
                        <input type="date" name="start_date" class="form-control me-2" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" class="form-control me-2" value="{{ request('end_date') }}">
                        <button type="submit" class="btn bg-gradient-info mb-0">Filter</button>
                    </form>

                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama Motor
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Plat Nomor
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tahun Motor
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Pajak
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Foto Motor
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        PIC
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($motor as $key => $item)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $motor->firstItem() + $key }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><strong>{{ $item->nama_motor }}</strong></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><strong>{{ $item->plat_nomor }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->tahun_motor }}</strong></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->tanggal_pajak->format('d M, Y') }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->foto_motor)
                                            <a href="javascript:void(0)">
                                                <img src="{{ asset('storage/motors/' . $item->foto_motor) }}" alt="foto motor" class="avatar avatar-sm">
                                            </a>
                                        @else
                                            <p class="text-xs font-weight-bold mb-0">No Image</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->karyawan->nama }}</p>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" data-bs-original-title="Edit">
                                            <i class="fas fa-pencil-alt text-secondary"></i>
                                        </a>
                                        <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $item->id }});">
                                            <i class="fas fa-trash text-secondary"></i>
                                        </a>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('motor.destroy', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center p-2">
                            {{ $motor->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                        
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
                <h5 class="modal-title" id="createModalLabel">Tambah Motor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('motor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_motor" class="form-label">Nama Motor</label>
                        <input type="text" class="form-control" id="nama_motor" name="nama_motor" placeholder="Masukkan nama motor" required>
                    </div>
                    <div class="mb-3">
                        <label for="plat_nomor" class="form-label">Plat Nomor</label>
                        <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" placeholder="Masukkan plat nomor" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_motor" class="form-label">Tahun Motor</label>
                        <input type="number" class="form-control" id="tahun_motor" name="tahun_motor" placeholder="Masukkan tahun motor" required>
                    </div> 
                    <div class="mb-3">
                        <label for="tanggal_pajak" class="form-label">Tanggal Pajak</label>
                        <input type="date" class="form-control" id="tanggal_pajak" name="tanggal_pajak" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto_motor" class="form-label">Foto Motor</label>
                        <input type="file" class="form-control" id="foto_motor" name="foto_motor">
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
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal Edit -->
@foreach ($motor as $item)
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Motor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('motor.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_motor_{{ $item->id }}" class="form-label">Nama Motor</label>
                        <input type="text" class="form-control" id="nama_motor_{{ $item->id }}" name="nama_motor" value="{{ $item->nama_motor }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="plat_nomor_{{ $item->id }}" class="form-label">Plat Nomor</label>
                        <input type="text" class="form-control" id="plat_nomor_{{ $item->id }}" name="plat_nomor" value="{{ $item->plat_nomor }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_motor_{{ $item->id }}" class="form-label">Tahun Motor</label>
                        <input type="number" class="form-control" id="tahun_motor_{{ $item->id }}" name="tahun_motor" value="{{ $item->tahun_motor }}" required>
                    </div>                    
                    <div class="mb-3">
                        <label for="tanggal_pajak_{{ $item->id }}" class="form-label">Tanggal Pajak</label>
                        <input type="date" class="form-control" id="tanggal_pajak_{{ $item->id }}" name="tanggal_pajak" value="{{ $item->tanggal_pajak->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto_motor_{{ $item->id }}" class="form-label">Foto Motor</label>
                        <input type="file" class="form-control" id="foto_motor" name="foto_motor">
                        
                        @if($item->foto_motor)
                            <img src="{{ asset('storage/motors/' . $item->foto_motor) }}" class="img-thumbnail mt-2" alt="Foto Motor" style="max-width: 100px;">
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
