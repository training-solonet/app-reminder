@extends('layouts.user_type.auth')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div>
                                <h5 class="mb-0">Daftar Pajak</h5>
                            </div>
                            <div class="d-flex flex-column flex-md-row">
                                <a href="/download-excel" class="btn bg-gradient-success btn-sm mb-2 mb-md-0 me-0 me-md-2" type="button">
                                    Download Template
                                    &nbsp;
                                    <i class="fas fa-solid fa-download" style="font-size: 11px"></i>
                                </a>
                                <a href="#" class="btn bg-gradient-info btn-sm mb-2 mb-md-0 me-0 me-md-2" type="button" data-bs-toggle="modal" data-bs-target="#ImportModal">
                                    Import Excel
                                    &nbsp;
                                    <i class="fas fa-solid fa-file-import" style="font-size: 11px"></i>
                                </a>
                                <a href="#" class="btn bg-gradient-danger btn-sm mb-2 mb-md-0" type="button" data-bs-toggle="modal" data-bs-target="#Import">
                                    Export CSV
                                    &nbsp;
                                    <i class="fas fa-solid fa-file-export" style="font-size: 11px"></i>
                                </a>
                            </div>
                        </div>
                        
                        <form class="d-flex flex-column flex-md-row mt-4 p-1">
                            <input type="date" name="tanggal_awal" class="form-control mb-2 mb-md-0 me-md-2" placeholder="Tanggal Mulai">
                            <input type="date" name="tanggal_akhir" class="form-control mb-2 mb-md-0 me-md-2" placeholder="Tanggal Akhir">
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
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Faktur</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama User</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DPP</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PPN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pajak as $key => $pajaks)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $key+1 }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0"><strong>{{ $pajaks->no_faktur }}</strong></p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pajaks->nama_user }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($pajaks->total, 0, ',', '.') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($pajaks->dpp, 0, ',', '.') }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($pajaks->ppn, 0, ',', '.') }}</p>
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


<div class="modal fade" id="ImportModal" tabindex="-1" aria-labelledby="ImportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ImportModalLabel">Import Excel File</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="importForm" enctype="multipart/form-data" action="{{ route('import-proses') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="formFile" class="form-label">Choose Excel file</label>
                    <input class="form-control" type="file" id="formFile" name="file" accept=".xls,.xlsx">
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn bg-gradient-info" id="uploadBtn">Upload</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Spinner Loading -->
  <div id="loading-spinner" style="display:none; position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); z-index: 9999;">
    <div style="border: 8px solid #f3f3f3; border-radius: 50%; border-top: 8px solid #3498db; width: 60px; height: 60px; animation: spin 1s linear infinite;"></div>
  </div>

  <!-- CSS untuk spinner -->
  <style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  </style>


  <script>
    document.getElementById('uploadBtn').addEventListener('click', function() {
        const form = document.getElementById('importForm');
        const fileInput = document.getElementById('formFile');
        const loadingSpinner = document.getElementById('loading-spinner'); 

        if (fileInput.files.length > 0) {

            loadingSpinner.style.display = 'block';

            form.submit();
        } else {
            alert('Please select a file.');
        }
    });
  </script>

@endsection
