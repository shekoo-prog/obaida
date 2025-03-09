<?php

namespace App\Controllers;

use App\Core\FileUpload\FileUploader;
use App\Core\Auth\Auth;

class FileController extends BaseController
{
    protected $uploader;
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->uploader = new FileUploader($this->auth->id());
    }

    public function upload()
    {
        if (!isset($_FILES['files'])) {
            return json_encode(['error' => 'No files uploaded']);
        }

        $this->uploader
            ->setMaxSize(300 * 1024 * 1024)
            ->setAllowedTypes([
                'image/jpeg' => 'jpg',
                'image/png'  => 'png'
            ])
            ->setCompressionQuality(75);

        $files = $this->restructureFiles($_FILES['files']);
        $results = $this->uploader->uploadMultiple($files);

        return json_encode([
            'success' => true,
            'files' => $results,
            'progress' => $this->uploader->getUploadProgress()
        ]);
    }

    protected function restructureFiles($files)
    {
        $restructured = [];
        foreach ($files as $key => $all) {
            foreach ($all as $i => $val) {
                $restructured[$i][$key] = $val;
            }
        }
        return $restructured;
    }
}