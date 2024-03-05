{{-- resources/views/upload/index.blade.php --}}

@extends('layouts.admin')

@section('content')
    <div class="container">
        <h3>Uploaded Files</h3>
        <a href="{{ route('upload.create') }}" class="btn btn-success">Upload New File</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Folder</th>
                    <th>File Path</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($uploads as $upload)
                    <tr>
                        <td>{{ $upload->name }}</td>
                        <td>{{ $upload->folder }}</td>
                        <td>{{ $upload->file_path }}</td>
                        <td>
                            <!-- Aksi yang diinginkan, misal: download link, view, atau edit -->
                            <a href="{{ asset('storage/' . $upload->file_path) }}" target="_blank"
                                class="btn btn-success">View</a>
                            <a href="{{ route('upload.download', $upload->id) }}" class="btn btn-primary">Download</a>
                            <a href="{{ route('upload.edit', $upload->id) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('upload.destroy', $upload->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
