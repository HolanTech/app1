<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Upload;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = Upload::all();
        return view("upload.index", compact('uploads'));
    }

    public function create()
    {
        return view("upload.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'folder' => 'required|string',
            'file' => 'required|file',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $fileName = $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs($request->folder, $fileName, 'public');

            $upload = new Upload();
            $upload->name = $request->name;
            $upload->folder = $request->folder;
            $upload->file_path = $filePath;
            $upload->save();

            $client = new Client();
            try {
                $response = $client->request('POST', 'http://app2.example.com/api/endpoint', [
                    'headers' => [
                        'Authorization' => 'Bearer your_api_token',
                        'Accept'        => 'application/json',
                    ],
                    'json' => [
                        'name' => $request->name,
                        'folder' => $request->folder,
                        'file_path' => $filePath,
                    ]
                ]);

                if ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) {
                    // Berhasil mengirim data, mungkin log atau simpan status ini
                    Log::info("Data successfully sent to the second app.");
                }
            } catch (\Exception $e) {
                Log::error("Error sending data to the second app: " . $e->getMessage());
                // Anda bisa memutuskan untuk tetap melanjutkan atau menghentikan proses dengan error
            }

            return redirect()->route('upload.index')->with('success', 'File successfully uploaded and data sent to the second app.');
        }

        return redirect()->back()->with('error', 'There was an issue uploading the file.');
    }


    public function download(Upload $upload)
    {
        if ($upload->file_path) { // Pastikan bahwa file_path ada
            if (Storage::disk('public')->exists($upload->file_path)) { // Periksa keberadaan file
                return Storage::disk('public')->download($upload->file_path);
            } else {
                return back()->with('error', 'File not found.');
            }
        }
        return back()->with('error', 'Invalid file request.');
    }


    public function edit(Upload $upload)
    {
        return view('upload.edit', compact('upload'));
    }

    public function update(Request $request, Upload $upload)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'folder' => 'required|string',
            'file' => 'sometimes|file',
        ]);

        $upload->name = $request->name;
        $upload->folder = $request->folder;

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            Storage::disk('public')->delete($upload->file_path);

            $fileName = $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs($upload->folder, $fileName, 'public');
            $upload->file_path = $filePath;
        }

        $upload->save();

        $client = new Client();
        try {
            $response = $client->request('PUT', 'http://test.yayasan-almuttaqien.or.id/api/receive-data/' . $upload->id, [
                'headers' => [
                    'Authorization' => '1a2s3d4f5g',
                    'Accept'        => 'application/json',
                ],
                'json' => [
                    'name' => $request->name,
                    'folder' => $request->folder,
                    'file_path' => $upload->file_path, // Assumed this is the correct attribute
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                Log::info("Data successfully updated in the second app.");
            }
        } catch (\Exception $e) {
            Log::error("Error updating data in the second app: " . $e->getMessage());
        }

        return redirect()->route('upload.index')->with('success', 'File successfully updated and data sent to the second app.');
    }


    public function destroy(Upload $upload)
    {
        if (Storage::disk('public')->exists($upload->file_path)) {
            Storage::disk('public')->delete($upload->file_path);
            \Log::info("File deleted successfully.");
        } else {
            \Log::error("File does not exist: " . $upload->file_path);
        }

        $upload->delete();

        return redirect()->route('upload.index')->with('success', 'File successfully deleted.');
    }
}
