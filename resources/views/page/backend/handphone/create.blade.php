@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-white fw-bold mb-0">➕ Tambah Handphone</h5>
            <a href="{{ route('handphone.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- Error alert --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('handphone.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label text-white">Brand</label>
                    <input type="text" name="brand" class="form-control bg-dark text-white border-0" value="{{ old('brand') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Model</label>
                    <input type="text" name="model" class="form-control bg-dark text-white border-0" value="{{ old('model') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label text-white">Release Year</label>
                    <select name="release_year" class="form-select bg-dark text-white border-0" required>
                        <option value="">-- Pilih Tahun --</option>
                        @for ($year = date('Y'); $year >= 2000; $year--)
                            <option value="{{ $year }}" {{ old('release_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Gambar</label>
                    <input type="file" name="image" class="form-control bg-dark text-white border-0">
                </div>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label text-white">Status</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_active" id="active" value="active" {{ old('is_active', 'active') == 'active' ? 'checked' : '' }}>
                    <label class="form-check-label text-white" for="active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_active" id="nonactive" value="nonactive" {{ old('is_active') == 'nonactive' ? 'checked' : '' }}>
                    <label class="form-check-label text-white" for="nonactive">Nonactive</label>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection