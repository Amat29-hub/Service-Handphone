@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <h4 class="fw-bold text-white mb-4">✏️ Edit Service Item</h4>

        <form action="{{ route('service-item.update', $service_item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="service_name" class="form-label fw-bold">Nama Service</label>
                <input type="text" name="service_name" id="service_name" class="form-control bg-dark text-light border-0"
                    value="{{ old('service_name', $service_item->service_name) }}" required>
                @error('service_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label fw-bold">Harga</label>
                <input type="number" name="price" id="price" class="form-control bg-dark text-light border-0"
                    value="{{ old('price', $service_item->price) }}" required>
                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="is_active" class="form-label fw-bold">Status</label>
                <select name="is_active" id="is_active" class="form-select bg-dark text-light border-0" required>
                    <option value="active" {{ old('is_active', $service_item->is_active) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('is_active', $service_item->is_active) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('service-item.index') }}" class="btn btn-light btn-sm me-2">Kembali</a>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection