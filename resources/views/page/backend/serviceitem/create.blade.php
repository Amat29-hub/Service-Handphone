@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Tambah Service Item</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <form action="{{ route('serviceitem.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="service_name" class="form-label fw-bold">Nama Service</label>
                <input type="text" name="service_name" id="service_name" 
                       class="form-control bg-dark text-light border-0" 
                       placeholder="Contoh: Ganti LCD, Instal Ulang" 
                       value="{{ old('service_name') }}" required>
                @error('service_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label fw-bold">Harga (Rp)</label>
                <input type="number" name="price" id="price" 
                       class="form-control bg-dark text-light border-0" 
                       placeholder="Masukkan harga" 
                       value="{{ old('price') }}" required>
                @error('price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Status</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_active" id="active" value="active" 
                               {{ old('is_active', 'active') === 'active' ? 'checked' : '' }}>
                        <label class="form-check-label text-light" for="active">Aktif</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_active" id="nonactive" value="nonactive" 
                               {{ old('is_active') === 'nonactive' ? 'checked' : '' }}>
                        <label class="form-check-label text-light" for="nonactive">Tidak Aktif</label>
                    </div>
                </div>
                @error('is_active')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('serviceitem.index') }}" class="btn btn-light me-2">Kembali</a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection