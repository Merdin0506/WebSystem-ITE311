<?php

namespace App\Controllers;

use App\Models\MaterialsModel;
use App\Models\EnrollmentModel;

class Materials extends BaseController
{
    public function upload(int $course_id)
    {
        if ($this->request->getMethod() === 'post') {
            helper(['form', 'url']);

            $rules = [
                'file' => [
                    'label'  => 'Material File',
                    'rules'  => 'uploaded[file]'
                               . '|max_size[file,5120]'
                               . '|ext_in[file,pdf,doc,docx,ppt,pptx,zip]'
                               . '|mime_in[file,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/zip]',
                    'errors' => [
                        'uploaded' => 'Please choose a file to upload.',
                        'max_size' => 'The file size must not exceed 5MB.',
                        'ext_in'   => 'Allowed file types: pdf, doc, docx, ppt, pptx, zip.',
                        'mime_in'  => 'The selected file type is not allowed.',
                    ],
                ],
            ];

            if (! $this->validate($rules)) {
                $errors = $this->validator ? $this->validator->getErrors() : ['validation' => 'Validation failed.'];
                return redirect()->back()->with('error', implode("\n", $errors))->withInput();
            }

            $file = $this->request->getFile('file');
            if (! $file || ! $file->isValid() || $file->hasMoved()) {
                return redirect()->back()->with('error', 'Invalid file upload.');
            }

            $originalName = $file->getClientName();
            $randomName   = $file->getRandomName();

            $targetDir = WRITEPATH . 'uploads/materials';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0775, true);
            }

            if (! $file->move($targetDir, $randomName)) {
                return redirect()->back()->with('error', 'Failed to move the uploaded file.');
            }

            $relativePath = 'uploads/materials/' . $randomName;

            $model = new MaterialsModel();
            $saved = $model->insertMaterial([
                'course_id'  => $course_id,
                'file_name'  => $originalName,
                'file_path'  => $relativePath,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($saved === false) {
                return redirect()->back()->with('error', 'Failed to save material to database.');
            }

            return redirect()->to('/admin/courses')->with('success', 'File uploaded successfully.');
        }

        return view('materials/upload', ['course_id' => $course_id]);
    }

    public function delete(int $material_id)
    {
        $model = new MaterialsModel();
        $material = $model->find($material_id);
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found');
        }

        $fullPath = WRITEPATH . $material['file_path'];
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }

        $model->delete($material_id);

        return redirect()->back()->with('message', 'Material deleted');
    }

    public function download(int $material_id)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to download materials.');
        }

        $model = new MaterialsModel();
        $material = $model->find($material_id);
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found');
        }

        // Verify the current user is enrolled in the course for this material
        $userId = (int) session()->get('userID');
        $enrollmentModel = new EnrollmentModel();
        if (! $enrollmentModel->isAlreadyEnrolled($userId, (int) $material['course_id'])) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $fullPath = WRITEPATH . $material['file_path'];
        if (!is_file($fullPath)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return $this->response->download($fullPath, null)->setFileName($material['file_name']);
    }

    public function listByCourse(int $course_id)
    {
        $model = new MaterialsModel();
        $materials = $model->getMaterialsByCourse($course_id);

        return view('materials/list', [
            'course_id' => $course_id,
            'materials' => $materials,
        ]);
    }
}
