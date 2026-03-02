@extends('layouts.app')

@section('title', 'Cetak Label Barang')

@section('page-title', 'Cetak Label Barang')
@section('page-icon', 'mdi-printer')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cetak Label</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cetak Label Tom n Jerry no 108</h4>
                <p class="card-description">Pilih barang yang akan dicetak dan tentukan posisi awal</p>
                
                <form action="{{ route('barang.print-label') }}" method="POST" target="_blank">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title">Posisi Label Awal</h5>
                                    <p class="text-muted">Tentukan koordinat X dan Y untuk posisi awal cetak</p>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="x">Koordinat X (Kolom)</label>
                                                <input type="number" class="form-control @error('x') is-invalid @enderror" 
                                                       id="x" name="x" value="{{ old('x', 1) }}"
                                                       min="1" max="5" required>
                                                <small class="form-text text-muted">Kolom 1-5</small>
                                                @error('x')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="y">Koordinat Y (Baris)</label>
                                                <input type="number" class="form-control @error('y') is-invalid @enderror" 
                                                       id="y" name="y" value="{{ old('y', 1) }}"
                                                       min="1" max="8" required>
                                                <small class="form-text text-muted">Baris 1-8</small>
                                                @error('y')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info mt-3">
                                        <i class="mdi mdi-information"></i>
                                        <strong>Info:</strong> Kertas label 108 memiliki 5 kolom × 8 baris = 40 label per lembar
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title">Ilustrasi Kertas Label</h5>
                                    <div class="text-center">
                                        <small class="text-muted">5 Kolom × 8 Baris</small>
                                        <div class="mt-2 p-3 bg-light border" style="display: inline-block;">
                                            @for($row = 1; $row <= 8; $row++)
                                                <div style="display: flex; justify-content: center;">
                                                    @for($col = 1; $col <= 5; $col++)
                                                        <div style="width: 40px; height: 30px; border: 1px solid #ccc; margin: 2px; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                                                            {{ $col }},{{ $row }}
                                                        </div>
                                                    @endfor
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border">
                        <div class="card-body">
                            <h5 class="card-title">Pilih Barang</h5>
                            <p class="text-muted">Centang barang yang akan dicetak labelnya</p>
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="selectAll()">
                                    <i class="mdi mdi-checkbox-multiple-marked"></i> Pilih Semua
                                </button>
                                <button type="button" class="btn btn-sm btn-light" onclick="deselectAll()">
                                    <i class="mdi mdi-checkbox-multiple-blank-outline"></i> Batal Pilih
                                </button>
                            </div>
                            
                            @error('barang_ids')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">
                                                <input type="checkbox" id="checkAll" onclick="toggleAll(this)">
                                            </th>
                                            <th width="10%">ID</th>
                                            <th>Nama Barang</th>
                                            <th width="20%">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barang as $item)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="barang_ids[]" value="{{ $item->id_barang }}" class="barang-checkbox">
                                            </td>
                                            <td><span class="badge bg-primary">{{ $item->id_barang }}</span></td>
                                            <td>{{ $item->nama }}</td>
                                            <td><strong>Rp {{ number_format($item->harga, 0, ',', '.') }}</strong></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada data barang</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-printer"></i> Generate Label PDF
                        </button>
                        <a href="{{ route('barang.index') }}" class="btn btn-light btn-lg">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleAll(source) {
    const checkboxes = document.querySelectorAll('.barang-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.barang-checkbox');
    const checkAll = document.getElementById('checkAll');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    checkAll.checked = true;
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('.barang-checkbox');
    const checkAll = document.getElementById('checkAll');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    checkAll.checked = false;
}

// Update checkAll status when individual checkboxes change
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.barang-checkbox');
    const checkAll = document.getElementById('checkAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkAll.checked = allChecked;
        });
    });
});
</script>
@endpush
