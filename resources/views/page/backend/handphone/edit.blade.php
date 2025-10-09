@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 text-white">Edit Handphone</h6>
            <a href="{{ route('handphone.index') }}" class="btn btn-outline-light btn-sm">Kembali</a>
        </div>

        <form action="{{ route('handphone.update', $handphone->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label text-white">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ $handphone->brand }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Model</label>
                    <input type="text" name="model" class="form-control" value="{{ $handphone->model }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label text-white">Release Year</label>
                    <select name="release_year" class="form-control">
                        <option value="">-- Pilih Tahun --</option>
                        @for ($year = date('Y'); $year >= 2000; $year--)
                            <option value="{{ $year }}" {{ $handphone->release_year == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Image</label>
                    <input type="file" name="image" class="form-control">
                    @if ($handphone->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $handphone->image) }}" width="120" class="rounded">
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Perbarui</button>
        </form>
    </div>
</div>
@endsection
