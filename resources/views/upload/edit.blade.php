@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Edit File</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('upload.update', $upload->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Nama File</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nama File"
                            value="{{ $upload->name }}">
                    </div>
                    <div class="col-12">
                        <label for="folder" class="form-label">Pilih Folder</label>
                        <select name="folder" id="folder" class="form-select">
                            <option value="">Pilih Folder</option>
                            <option value="doc" {{ $upload->folder == 'doc' ? 'selected' : '' }}>Document</option>
                            <option value="img" {{ $upload->folder == 'img' ? 'selected' : '' }}>Image</option>
                            <option value="video" {{ $upload->folder == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="kmz" {{ $upload->folder == 'kmz' ? 'selected' : '' }}>KMZ</option>
                            <option value="kml" {{ $upload->folder == 'kml' ? 'selected' : '' }}>KML</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" name="file" id="file">
                        @if ($upload->file_path)
                            <div class="mt-2">
                                Current File: <a href="{{ Storage::url($upload->file_path) }}" target="_blank">View File</a>
                            </div>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@endsection
