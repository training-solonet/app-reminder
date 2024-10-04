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
                    <form action="{{ route('transaksi_domain.index') }}" method="GET" class="d-flex mt-4 p-1">
                        <input type="date" name="tanggal_filter" class="form-control me-2" value="{{ request('tanggal_filter', $tanggalFilter) }}">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari Nama Domain/Status" value="{{ request('search') }}">
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
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Masa Perpanjang</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Transaksi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bukti</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
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
                                    <p class="text-xs font-weight-bold mb-0"><strong>{{ $trans->domain->nama_domain }}</strong></p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $trans->masa_perpanjang }} Tahun</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($trans->nominal, 0, ',', '.') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $trans->tgl_transaksi->format('d M, Y') }}</p>
                                </td>
                                <td class="text-center">
                                        @if ($trans->bukti)
                                            <a href="javascript:void(0)">
                                                <img src="{{ asset('storage/buktidomain/' . $trans->bukti) }}" alt="bukti" class="avatar avatar-sm">
                                            </a>
                                        @else
                                            <p class="text-xs font-weight-bold mb-0">No Image</p>
                                        @endif
                                    </td>
                                </td>
                                <td class="text-center">
                                    @if ($trans->status == 'lunas')
                                            <p class="badge badge-sm bg-gradient-success mb-0">{{ $trans->status }}</p>
                                    @else
                                        <p class="badge badge-sm bg-gradient-danger mb-0">{{ $trans->status }}</p>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $trans->id }}" data-bs-original-title="Edit">
                                        <i class="fas fa-pencil-alt text-secondary"></i>
                                    </a>
                                    <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $trans->id }});">
                                        <i class="fas fa-trash text-secondary"></i>
                                    </a>
                                    <form id="delete-form-{{ $trans->id }}" action="{{ route('transaksi_domain.destroy', $trans->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                        <div class="d-flex justify-content-center p-2">
                            {{ $transaksis->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
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
                                <option value="{{ $d->id }}" data-nominal="{{ $d->nominal }}">{{ $d->nama_domain }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="masa_perpanjang" class="form-label">Masa Perpanjang (Tahun)</label>
                        <input type="number" class="form-control" id="masa_perpanjang" name="masa_perpanjang" placeholder="Masa Perpanjang *minimal: 1" value="1" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" placeholder="Nominal" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_transaksi" class="form-label">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi" required>
                    </div>
                    <div class="mb-3">
                        <label for="bukti" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*">
                    </div>
                    <input type="hidden" name="status" value="lunas">
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('domain_id').addEventListener('change', function() {
        var selected = this.options[this.selectedIndex];
        var nominal = selected.getAttribute('data-nominal');
        var nominalTanpaDesimal = Math.floor(nominal);
        var masaPerpanjang = document.getElementById('masa_perpanjang').value;

        document.getElementById('nominal').value = nominalTanpaDesimal * masaPerpanjang;
    });

    document.getElementById('masa_perpanjang').addEventListener('input', function() {
        var masaPerpanjang = this.value;
        var selected = document.getElementById('domain_id').options[document.getElementById('domain_id').selectedIndex];
        var nominal = selected.getAttribute('data-nominal');
        var nominalTanpaDesimal = Math.floor(nominal);

        document.getElementById('nominal').value = nominalTanpaDesimal * masaPerpanjang;
    });
</script>

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
                    <input type="hidden" name="domain_id" value="{{ $trans->domain_id }}">
                    <div class="mb-3">
                        <label for="nama_domain{{ $trans->id }}" class="form-label">Nama Domain</label>
                        <input type="text" class="form-control" id="nama_domain{{ $trans->id }}" value="{{ $trans->domain->nama_domain }}" readonly required>
                        <input type="hidden" id="nominal_asli_{{ $trans->id }}" value="{{ $trans->domain->nominal }}">
                    </div>
                    <div class="mb-3">
                        <label for="tgl_transaksi_{{ $trans->id }}" class="form-label">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tgl_transaksi_{{ $trans->id }}" name="tgl_transaksi" value="{{ $trans->tgl_transaksi->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="masa_perpanjang{{ $trans->id }}" class="form-label">Masa Perpanjang (Tahun)</label>
                        <input type="number" class="form-control" id="masa_perpanjang{{ $trans->id }}" name="masa_perpanjang" placeholder="Berapa tahun *Contoh: 1" value="{{ $trans->masa_perpanjang }}" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal{{ $trans->id }}" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="nominal{{ $trans->id }}" name="nominal" value="{{ number_format($trans->nominal, 0, '', '') }}" readonly>
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

<!-- Script untuk hitung otomatis nominal berdasarkan masa perpanjang -->
<script>
    @foreach($transaksis as $trans)
        document.getElementById('masa_perpanjang{{ $trans->id }}').addEventListener('input', function() {
            var masaPerpanjang = this.value;
            var nominalAsli = document.getElementById('nominal_asli_{{ $trans->id }}').value;
            document.getElementById('nominal{{ $trans->id }}').value = nominalAsli * masaPerpanjang;
        });
    @endforeach
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