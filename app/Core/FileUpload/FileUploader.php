<?php

namespace App\Core\FileUpload;

use App\Models\User;

class FileUploader
{
    protected $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx'
    ];

    protected $maxSize = 5242880; // 5MB in bytes
    protected $uploadPath;
    protected $errors = [];
    protected $user;
    protected $compressionQuality = 75;
    protected $uploadProgress = 0;

    public function __construct($userId = null)
    {
        $this->uploadPath = dirname(__DIR__, 3) . '/storage/uploads/' . date('Y/m');
        if ($userId) {
            $this->user = (new User())->find($userId);
        }
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function uploadMultiple($files)
    {
        $results = [];
        foreach ($files as $file) {
            $results[] = $this->upload($file);
            $this->uploadProgress = ($this->uploadProgress + (100 / count($files)));
        }
        return $results;
    }

    public function upload($file)
    {
        try {
            $this->validateStorageLimit($file['size']);
            $this->validateFile($file);
            
            if (!empty($this->errors)) {
                return false;
            }

            $fileName = $this->generateFileName($file);
            $destination = $this->uploadPath . '/' . $fileName;

            if ($this->isImage($file)) {
                $this->compressImage($file['tmp_name'], $destination);
            } else {
                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    $this->errors[] = 'Failed to move uploaded file.';
                    return false;
                }
            }

            $this->saveFileRecord($file, $fileName);
            return $fileName;

        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
    }

    protected function validateStorageLimit($fileSize)
    {
        if (!$this->user) {
            return true;
        }

        $usedStorage = $this->getUserStorageUsed();
        if (($usedStorage + $fileSize) > $this->user->storage_limit) {
            throw new \Exception('Storage limit exceeded');
        }
    }

    protected function getUserStorageUsed()
    {
        // Implementation to get total storage used by user
        return 0; // Placeholder
    }

    protected function compressImage($source, $destination)
    {
        $info = getimagesize($source);
        if ($info === false) {
            throw new \Exception('Invalid image file');
        }

        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($source);
                imagejpeg($image, $destination, $this->compressionQuality);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($source);
                imagepng($image, $destination, round($this->compressionQuality / 10));
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        imagedestroy($image);
    }

    protected function isImage($file)
    {
        return strpos($file['type'], 'image/') === 0;
    }

    protected function saveFileRecord($file, $fileName)
    {
        $data = [
            'user_id' => $this->user ? $this->user->id : null,
            'filename' => $fileName,
            'original_name' => $file['name'],
            'mime_type' => $file['type'],
            'size' => $file['size'],
            'path' => $this->uploadPath . '/' . $fileName
        ];

        // Implementation to save file record to database
    }

    public function getUploadProgress()
    {
        return $this->uploadProgress;
    }

    public function setCompressionQuality($quality)
    {
        $this->compressionQuality = max(0, min(100, $quality));
        return $this;
    }

    protected function validateFile($file)
    {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Upload failed with error code: ' . $file['error']);
        }

        // Check file size
        if ($file['size'] > $this->maxSize) {
            throw new \Exception('File size exceeds limit of ' . ($this->maxSize / 1048576) . 'MB');
        }

        // Check MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        
        if (!array_key_exists($mimeType, $this->allowedTypes)) {
            throw new \Exception('File type not allowed');
        }

        // Validate file content
        if (!$this->validateFileContent($file)) {
            throw new \Exception('Invalid file content');
        }
    }

    protected function validateFileContent($file)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        // Additional checks for images
        if (strpos($mimeType, 'image/') === 0) {
            $imageInfo = getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                return false;
            }
        }

        // Check for PHP code in files
        $content = file_get_contents($file['tmp_name']);
        if (strpos($content, '<?php') !== false) {
            return false;
        }

        return true;
    }

    protected function generateFileName($file)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $extension = $this->allowedTypes[$mimeType];
        
        return uniqid(date('Y-m-d-His-')) . '.' . $extension;
    }

    public function setMaxSize($sizeInBytes)
    {
        $this->maxSize = $sizeInBytes;
        return $this;
    }

    public function setAllowedTypes(array $types)
    {
        $this->allowedTypes = $types;
        return $this;
    }
}