@extends('layouts.user_type.auth')

@section('content')

@if($pengingat->count() > 0)
    @foreach($pengingat as $pengingats)
        @if($pengingats->status == 'aktif' && $pengingats->status_pelaksanaan == 'belum')
            @php
                $currentDateTime = \Carbon\Carbon::now();
                $reminderDateTime = \Carbon\Carbon::parse($pengingats->tanggal_reminder . ' ' . $pengingats->waktu_reminder);
            @endphp

            @if($currentDateTime->greaterThanOrEqualTo($reminderDateTime))
                <div class="alert alert-info alert-dismissible fade show mx-4" role="alert">
                    <span class="text-white">
                        <strong>Reminder!</strong> 
                        <strong>{{ $pengingats->tentang_reminder }}</strong> harus dilaksanakan sekarang.
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endif
    @endforeach
@endif

    
<div>
    <div class="row">   
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h5 class="mb-0">Reminder</h5>
                        
                        <div class="d-flex">
                            <form action="{{ route('reminders.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Cari Reminder..." value="{{ request('search') }}">
                                <button type="submit" class="btn bg-gradient-info btn-sm ms-3">Cari</button>
                            </form>
                            
                            <a href="#" class="btn bg-gradient-info btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#addReminderModal">
                                +&nbsp; Tambah Reminder
                            </a>
                        </div>
                    </div>


                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tentang Reminder</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Pelaksanaan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reminders as $key => $reminder)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $key + 1 }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><strong>{{ $reminder->tentang_reminder }}</strong></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $reminder->keterangan }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ \Carbon\Carbon::parse($reminder->tanggal_reminder)->format('d M, Y')}}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $reminder->waktu_reminder }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if($reminder->status == 'aktif')
                                            <p class="badge badge-sm bg-gradient-success mb-0">{{ $reminder->status }}</p>
                                        @else
                                            <p class="badge badge-sm bg-gradient-danger mb-0">{{ $reminder->status }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($reminder->status_pelaksanaan == 'sudah')
                                            <p class="badge badge-sm bg-gradient-success mb-0">{{ $reminder->status_pelaksanaan }}</p>
                                        @else
                                            <p class="badge badge-sm bg-gradient-danger mb-0">{{ $reminder->status_pelaksanaan }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#detailModal{{ $reminder->id }}">
                                            <i class="fas fa-eye text-secondary"></i>
                                        </a>
                                        <a href="#" class="p-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $reminder->id }}">
                                            <i class="fas fa-pencil-alt text-secondary"></i>
                                        </a>
                                        <a href="#" class="p-1" onclick="event.preventDefault(); confirmDelete({{ $reminder->id }});">
                                            <i class="fas fa-trash text-secondary"></i>
                                        </a>
                                        <form id="delete-form-{{ $reminder->id }}" action="{{ route('reminders.destroy', $reminder->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="mx-4 mt-3">
                            {{ $reminders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($reminders as $reminder)
<!-- Modal Detail Reminder -->
<div class="modal fade" id="detailModal{{ $reminder->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Reminder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $fields = [
                        'Tentang Reminder' => $reminder->tentang_reminder,
                        'Keterangan' => $reminder->keterangan ?? 'Tidak ada keterangan',
                        'Tanggal Reminder' => \Carbon\Carbon::parse($reminder->tanggal_reminder)->format('d M, Y'),
                        'Waktu Reminder' => $reminder->waktu_reminder,
                        'Status' => ucfirst($reminder->status),
                        'Status Pelaksanaan' => ucfirst($reminder->status_pelaksanaan)
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

<!-- Modal Tambah Reminder -->
<div class="modal fade" id="addReminderModal" tabindex="-1" aria-labelledby="addReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReminderModalLabel">Tambah Reminder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reminders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="tentang_reminder" class="form-label">Tentang Reminder</label>
                        <input type="text" class="form-control" id="tentang_reminder" name="tentang_reminder" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_reminder" class="form-label">Tanggal Reminder</label>
                        <input type="date" class="form-control" id="tanggal_reminder" name="tanggal_reminder" required>
                    </div>
                    <div class="mb-3">
                        <label for="waktu_reminder" class="form-label">Waktu Reminder</label>
                        <input type="time" class="form-control" id="waktu_reminder" name="waktu_reminder" required>
                    </div>
                    <input type="hidden" name="status" value="tidak-aktif">
                    <input type="hidden" name="status_pelaksanaan" value="belum">
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Reminder -->
@foreach ($reminders as $reminder)
<div class="modal fade" id="editModal{{ $reminder->id }}" tabindex="-1" aria-labelledby="editReminderModalLabel{{ $reminder->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReminderModalLabel">Edit Reminder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reminders.update', $reminder->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_tentang_reminder" class="form-label">Tentang Reminder</label>
                        <input type="text" class="form-control" id="edit_tentang_reminder" name="tentang_reminder" value="{{ $reminder->tentang_reminder }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan">{{ $reminder->keterangan }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal_reminder" class="form-label">Tanggal Reminder</label>
                        <input type="date" class="form-control" id="edit_tanggal_reminder" name="tanggal_reminder" value="{{ $reminder->tanggal_reminder }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_waktu_reminder" class="form-label">Waktu Reminder</label>
                        <input type="time" class="form-control" id="edit_waktu_reminder" name="waktu_reminder" value="{{ $reminder->waktu_reminder }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status_pelaksanaan" class="form-label">Status Pelaksanaan</label>
                        <select class="form-control" id="edit_status_pelaksanaan" name="status_pelaksanaan">
                            <option value="sudah" {{ $reminder->status_pelaksanaan == 'sudah' ? 'selected' : '' }}>Sudah</option>
                            <option value="belum" {{ $reminder->status_pelaksanaan == 'belum' ? 'selected' : '' }}>Belum</option>
                        </select>
                    </div>
                    <button type="submit" class="btn bg-gradient-info">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
        
@endsection
