@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 text-white">Tambah Handphone</h6>
            <a href="{{ route('handphone.index') }}" class="btn btn-outline-light btn-sm">Kembali</a>
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
                    <input type="number" name="release_year" class="form-control" min="2000" max="{{ date('Y') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Simpan</button>
        </form>
    </div>
</div>
@endsection
