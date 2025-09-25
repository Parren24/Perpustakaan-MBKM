<?php

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Str;

/**
 * @author Delza Biondy <email>
 * @d
 */
if (!function_exists('uploadMedia')) {

    function uploadMedia($key, $default_path = 'media')
    {
        $request = app(Request::class);
        try {
            if (!Storage::disk('public')->exists($default_path)) {
                Storage::disk('public')->makeDirectory($default_path);
            }

            if (!$request->hasFile($key)) {
                return [
                    'status' => false,
                    'message' => 'No file uploaded.',
                ];
            }

            $file = $request->file($key);

            if (!$file->isValid()) {
                return [
                    'status' => false,
                    'message' => 'File is not valid.',
                ];
            }

            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs($default_path, $filename, 'public');

            if (!Storage::disk('public')->exists($path)) {
                return [
                    'status' => false,
                    'message' => 'File gagal disimpan ke storage.',
                    'data' => ['path' => $path],
                ];
            }

            return [
                'status' => true,
                'message' => 'File uploaded',
                'data' => [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'extension' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(), // dalam byte
                    'path' => $path, // relatif terhadap disk
                ],
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}

/**
 *
 * Removed because changes in table has media
 *
 *
function uploadMedia($key, $file_info = null, $default_path = '', $id_encryption = true)
{
    if ($default_path != '') {
        if (!Storage::exists($default_path)) {
            Storage::makeDirectory($default_path);
        }
    }

    $request = app(Request::class);

    if (!$request->hasFile($key)) {
        return [
            'status'  => false,
            'message' => 'No file uploaded.',
        ];
    }

    $file = $request->file($key);

    if (!$file->isValid()) {
        return [
            'status'  => false,
            'message' => 'Uploaded file is invalid.',
        ];
    }

    try {
        $path = $file->store($default_path, 'public');

        $data = [];
        $data['mimetype_media']     = $file->getMimeType();
        $data['filesize_media']     = $file->getSize();
        $data['nama_media']         = $file_info && $file_info['nama_media'] ? $file_info['nama_media'] : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // $data['alt_text_media']     = $file_info && $file_info['alt_text_media'] ? $file_info['alt_text_media'] : $file->getClientOriginalName();
        $data['ext_media']          = $file->getClientOriginalExtension();
        $data['filepath_media']     = $path;

        $info_media = [];
        $info_media['original_filename'] = $file->getClientOriginalName();
        $info_media['hashed_filename'] = $file->hashName();

        if (in_array($data['ext_media'], ['jpg', 'jpeg', 'png'])) {
            try {
                // Get width and height of the image
                list($width, $height) = getimagesize($file->getPathName());

                // Add width and height to the details array
                $info_media['width'] = $width;
                $info_media['height'] = $height;

                // Generate Thumbnail (300x300 by cropping from center)
                $thumbnailPath = $default_path . pathinfo($path, PATHINFO_FILENAME) . '_thumb.' . $data['ext_media'];
                $thumbnailPath = createMediaThumbnail($path, 300, 300);
                $data['thumb_media'] = $thumbnailPath;
            } catch (\Exception $e) {
                return [
                    'status'  => false,
                    'message' => $e->getMessage()
                ];
            }
        } else {
            $data['thumb_media']    = null;
        }

        $data['info_media']     = json_encode($info_media);

        $inserted_data = Media::create($data);
        if ($id_encryption) {
            $data['media_id'] = encid($inserted_data->media_id);
        } else {
            $data['media_id'] = $inserted_data->media_id;
        }

        return [
            'status'  => true,
            'data'    => $data
        ];
    } catch (\Throwable $th) {
        return [
            'status'  => false,
            'message' => $th->getMessage()
        ];
    }
}
function uploadMediaBase64($base64Image, $file_info = null, $default_path = '', $id_encryption = true)
{
    if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
        return [
            'status'  => false,
            'message' => 'Invalid image format'
        ];
    }

    try {
        $extension = strtolower($matches[1]);
        $decodedImage = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));

        if ($decodedImage === false) {
            return response()->json(['error' => 'Invalid image data'], 422);
        }

        // ✅ Generate unique filename using timestamp + uniqid
        $fileName = date('YmdHis') . uniqid() . '.' . $extension;

        // Relative path (based on default_path)
        $relativePath = "$default_path/$fileName";

        // Save file using Storage
        Storage::put($relativePath, $decodedImage);

        // Full path for image processing
        $fullPath = Storage::path($relativePath);
        $file = new File($fullPath);

        $data = [
            'mimetype_media' => $file->getMimeType(),
            'filesize_media' => $file->getSize(),
            'nama_media' => $fileName,
            'ext_media' => $extension,
            'filepath_media' => $relativePath,
        ];

        $info_media = [];

        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            try {
                [$width, $height] = getimagesize($file->getPathname());
                $info_media['width'] = $width;
                $info_media['height'] = $height;

                // ✅ Generate thumbnail filename with same prefix
                $thumbName = pathinfo($fileName, PATHINFO_FILENAME) . '_thumb.' . $extension;
                $thumbRelativePath = "$default_path/$thumbName";
                $thumbFullPath = Storage::path($thumbRelativePath);

                createMediaThumbnail($relativePath, $thumbFullPath, 300, 300);
                $data['thumb_media'] = $thumbRelativePath;
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            $data['thumb_media'] = null;
        }

        $data['info_media'] = json_encode($info_media);

        $inserted = Media::create($data);
        $data['media_id'] = $id_encryption ? encid($inserted->media_id) : $inserted->media_id;

        return [
            'status' => true,
            'data' => $data
        ];
    } catch (\Throwable $th) {
        return [
            'status' => false,
            'message' => $th->getMessage()
        ];
    }
}
/*function uploadMediaBase64($base64Image, $file_info = null, $default_path = 'media', $id_encryption = true)
{
    if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
        return [
            'status'  => false,
            'message' => 'Invalid image format'
        ];
    }

    try {
        $extension = $matches[1];

        $decodedImage = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));

        if ($decodedImage === false) {
            return response()->json(['error' => 'Invalid image data'], 422);
        }

        // Generate a unique file name
        $fileName = uniqid() . '.' . $extension;

        // Define the storage path
        $path = storage_path('app/media'); // You can use `storage_path` if saving in the `storage/app` folder
        if (!file_exists($path)) {
            mkdir($path, 0755, true); // Create directory if it doesn't exist
        }

        // Save the file
        $filePath = $path . '/' . $fileName;
        file_put_contents($filePath, $decodedImage);

        // Get file data like mimetype, size, and extension
        $file = new \Symfony\Component\HttpFoundation\File\File($filePath);  // Treat the base64 image as a file object

        $data = [];
        $data['mimetype_media'] = $file->getMimeType();  // Mime type
        $data['filesize_media'] = $file->getSize();      // File size
        $data['nama_media'] = $fileName;
        $data['ext_media'] = $extension;  // File extension
        $data['filepath_media'] = 'media/' . $fileName;  // Filepath relative to the storage folder

        $info_media = [];
        // $info_media['original_filename'] = $file->getClientOriginalName();
        // $info_media['hashed_filename'] = $file->hashName();

        // Handle image properties if it's a valid image
        if (in_array($data['ext_media'], ['jpg', 'jpeg', 'png'])) {
            try {
                // Get width and height of the image
                list($width, $height) = getimagesize($file->getPathName());

                // Add width and height to the details array
                $info_media['width'] = $width;
                $info_media['height'] = $height;

                // Generate Thumbnail (300x300 by cropping from center)
                $thumbnailPath = 'media/' . pathinfo($default_path . '/' . $fileName, PATHINFO_FILENAME) . '_thumb.' . $data['ext_media'];

                createMediaThumbnail($default_path . '/' . $fileName, storage_path('app/' . $thumbnailPath), 300, 300);
                $data['thumb_media'] = $thumbnailPath;
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            $data['thumb_media'] = null;
        }

        // Save additional info about the media
        $data['info_media'] = json_encode($info_media);

        // Insert the data into the database
        $inserted_data = Media::create($data);

        if ($id_encryption) {
            $data['media_id'] = encid($inserted_data->media_id);
        } else {
            $data['media_id'] = $inserted_data->media_id;
        }

        return [
            'status'  => true,
            'data'    => $data
        ];
    } catch (\Throwable $th) {
        return [
            'status'  => false,
            'message' => $th->getMessage()
        ];
    }
}

function createMediaThumbnail($filePath, $thumbnailPath, $width, $height)
{
    // Load the image based on its type
    $image = null;
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $fullFilePath = storage_path('app/' . $filePath);

    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($fullFilePath);
            break;
        case 'png':
            $image = imagecreatefrompng($fullFilePath);
            break;
        default:
            throw new Exception('Unsupported image type: ' . $extension);
    }

    // Get original dimensions
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    // Create a blank image for the thumbnail
    $thumbnail = imagecreatetruecolor($width, $height);

    // Calculate the crop dimensions (center crop)
    $cropWidth = $originalWidth > $originalHeight ? $originalHeight : $originalWidth;
    $cropHeight = $cropWidth;

    $srcX = ($originalWidth - $cropWidth) / 2;
    $srcY = ($originalHeight - $cropHeight) / 2;

    // Copy and resize the cropped region to the thumbnail
    imagecopyresampled(
        $thumbnail,
        $image,
        0,
        0,
        $srcX,
        $srcY,
        $width,
        $height,
        $cropWidth,
        $cropHeight
    );

    // if (!Storage::exists('media/thumbnails')) {
    //     Storage::makeDirectory('media/thumbnails');
    // }

    // Save the thumbnail as JPG or PNG based on the original extension
    if ($extension == 'png') {
        imagepng($thumbnail, $thumbnailPath);
    } else {
        imagejpeg($thumbnail, $thumbnailPath);
    }

    // Clean up
    imagedestroy($image);
    imagedestroy($thumbnail);
}

function createMediaThumbnail($filePath, $width, $height)
{
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    // Path penuh file asli (gunakan disk public)
    $fullFilePath = Storage::disk('public')->path($filePath);

    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($fullFilePath);
            break;
        case 'png':
            $image = imagecreatefrompng($fullFilePath);
            break;
        default:
            throw new Exception('Unsupported image type: ' . $extension);
    }

    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    $thumbnail = imagecreatetruecolor($width, $height);

    $cropSize = min($originalWidth, $originalHeight);
    $srcX = ($originalWidth - $cropSize) / 2;
    $srcY = ($originalHeight - $cropSize) / 2;

    imagecopyresampled(
        $thumbnail,
        $image,
        0,
        0,
        $srcX,
        $srcY,
        $width,
        $height,
        $cropSize,
        $cropSize
    );

    $folder = pathinfo($filePath, PATHINFO_DIRNAME);
    $filename = pathinfo($filePath, PATHINFO_FILENAME);
    $thumbFile = $folder . '/' . $filename . '_thumb.' . $extension;

    if (!Storage::disk('public')->exists($folder)) {
        Storage::disk('public')->makeDirectory($folder);
    }

    $fullThumbPath = Storage::disk('public')->path($thumbFile);

    if ($extension === 'png') {
        imagepng($thumbnail, $fullThumbPath);
    } else {
        imagejpeg($thumbnail, $fullThumbPath, 90);
    }

    imagedestroy($image);
    imagedestroy($thumbnail);

    return $thumbFile;
}



function destroyMedia($media_id, $permanent_delete = false)
{
    $media   = Media::find($media_id);

    // Jika media tidak ditemukan, return 404
    if (!$media) {
        abort(404, 'Media not found');
    }

    // Tentukan path relatif dari storage
    $relativePath = $media->filepath_media;
    $relativePathThumb = $media->thumb_media;

    try {
        // Pastikan file ada di storage
        if (!Storage::exists($relativePath) || !Storage::exists($relativePathThumb)) {
            abort(404, 'File not found in storage');
        }

        if ($permanent_delete) {
            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }

            if (Storage::exists($relativePathThumb)) {
                Storage::delete($relativePathThumb);
            }
        }

        $media->delete();

        return [
            'status'  => true,
            'message' => 'Media berhasil dihapus'
        ];
    } catch (\Throwable $th) {
        return [
            'status'  => false,
            'message' => $th->getMessage()
        ];
    }
}

function serveMedia($media_id, $is_thumb = false, $disposition = 'inline')
{
    $media   = Media::find($media_id);

    // Jika media tidak ditemukan, return 404
    if (!$media) {
        abort(404, 'Media not found');
    }

    // Tentukan path relatif dari storage
    $relativePath = $is_thumb ? $media->thumb_media : $media->filepath_media;

    // Pastikan file ada di storage
    if (!Storage::disk('public')->exists($relativePath)) {
        abort(404, 'File not found in storage');
    }

    // Dapatkan konten file dari storage
    $fileContent = Storage::disk('public')->get($relativePath);

    // Dapatkan informasi file
    $filename = basename($relativePath);
    $filesize = Storage::disk('public')->size($relativePath);
    $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));

    // Tentukan MIME type berdasarkan ekstensi file
    $mimeTypes = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt'  => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'txt'  => 'text/plain',
        'csv'  => 'text/csv',
        'zip'  => 'application/zip',
        'rar'  => 'application/x-rar-compressed',
        'json' => 'application/json',
        'xml'  => 'application/xml',
    ];

    $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

    // Tentukan disposition default untuk tipe file tertentu
    $downloadTypes = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];
    if (in_array($extension, $downloadTypes)) {
        $disposition = 'attachment';
    }

    // Buat respons HTTP untuk mengirimkan file
    return response($fileContent, 200, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => $disposition . '; filename="' . $filename . '"',
        'Content-Length' => $filesize,
        'Cache-Control' => 'public, max-age=2592000',
        'Pragma' => 'public',
        'Expires' => gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT',
    ]);
}

function serveMediaBase64($media_id, $is_thumb = false)
{
    $get_media = Media::find($media_id);

    if ($get_media && Storage::exists($is_thumb ? $get_media->thumb_media : $get_media->filepath_media)) {
        $imageData = Storage::get($is_thumb ? $get_media->thumb_media : $get_media->filepath_media);
        $mimeType = Storage::mimeType($is_thumb ? $get_media->thumb_media : $get_media->filepath_media);
        $base64Image = base64_encode($imageData);

        return "data:$mimeType;base64,$base64Image";
    } else {
        return null;
    }
}
 */
