@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Tambahkan File</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Nama File</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nama File">
                    </div>
                    <div class="col-12">
                        <label for="folder" class="form-label">Pilih Folder</label>
                        <select name="folder" id="folder" class="form-select">
                            <option value="">Pilih Folder</option>
                            <option value="doc">Document</option>
                            <option value="img">Image</option>
                            <option value="video">Video</option>
                            <option value="kmz">KMZ</option>
                            <option value="kml">KML</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" name="file" id="file">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
@endsection
