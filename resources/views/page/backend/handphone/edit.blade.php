@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Edit Handphone</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <a href="{{ route('handphone.index') }}" class="btn btn-sm btn-primary mb-3">Kembali</a>

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

        <form action="{{ route('handphone.update', $handphone->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Brand --}}
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand', $handphone->brand) }}" required>
            </div>

            {{-- Model --}}
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" name="model" id="model" class="form-control" value="{{ old('model', $handphone->model) }}" required>
            </div>

            {{-- Release Year --}}
            <div class="mb-3">
                <label for="release_year" class="form-label">Tahun Rilis</label>
                <select name="release_year" id="release_year" class="form-select" required>
                    <option value="">-- Pilih Tahun --</option>
                    @for ($year = date('Y'); $year >= 2000; $year--)
                        <option value="{{ $year }}" {{ old('release_year', $handphone->release_year) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Gambar --}}
            <div class="mb-3">
                <label for="image" class="form-label">Gambar</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                @if ($handphone->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $handphone->image) }}" alt="Gambar Handphone" width="120" class="rounded border">
                    </div>
                @endif
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_active" id="active" value="active" {{ old('is_active', $handphone->is_active) == 'active' ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_active" id="nonactive" value="nonactive" {{ old('is_active', $handphone->is_active) == 'nonactive' ? 'checked' : '' }}>
                    <label class="form-check-label" for="nonactive">Nonactive</label>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection