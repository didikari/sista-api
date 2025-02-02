<?php

namespace App\Services\Title;

use Illuminate\Http\Request;
use LaravelEasyRepository\BaseService;

interface TitleService extends BaseService
{
    public function getAllTitles();
    public function getTitleByStudent(Request $request);
    public function getTitleBySupervisor(Request $request);
    public function createTitle(array $data, $studentId);
    public function delete($id);
    public function updateTitle($id, array $data, $studentId);
    public function updateStatusBySupervisor($id, array $data);
}
