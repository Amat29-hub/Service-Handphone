@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-white fw-bold mb-0">📱 Tambah Handphone</h5>
            <a href="{{ route('handphone.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('handphone.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label text-white">Brand</label>
                    <input type="text" name="brand" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Model</label>
                    <input type="text" name="model" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label text-white">Release Year</label>
                    <select name="release_year" class="form-select" required>
                        <option value="">-- Pilih Tahun --</option>
                        @for ($year = date('Y'); $year >= 2000; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Gambar</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection