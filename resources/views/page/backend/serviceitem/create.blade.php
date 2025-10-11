@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Tambah Service Item</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold">Form Tambah Service Item</h6>
            <a href="{{ route('service-item.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('service-item.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="service_name" class="form-label text-light">Nama Service</label>
                    <input type="text" name="service_name" id="service_name" class="form-control bg-dark text-light border-0" placeholder="Contoh: Ganti LCD, Instal Ulang, dll" value="{{ old('service_name') }}" required>
                    @error('service_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="price" class="form-label text-light">Harga (Rp)</label>
                    <input type="number" name="price" id="price" class="form-control bg-dark text-light border-0" placeholder="Masukkan harga" value="{{ old('price') }}" required>
                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="is_active" class="form-label text-light">Status</label>
                    <select name="is_active" id="is_active" class="form-select bg-dark text-light border-0" required>
                        <option value="" disabled selected>Pilih status</option>
                        <option value="active" {{ old('is_active') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('is_active') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('is_active')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection