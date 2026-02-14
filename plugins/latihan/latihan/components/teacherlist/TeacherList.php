<?php namespace Latihan\Latihan\Components;

use Cms\Classes\ComponentBase;
use Latihan\Latihan\Models\Teacher;

class TeacherList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Teacher List',
            'description' => 'Menampilkan daftar guru dari database'
        ];
    }

    public function onRun()
    {
        $this->page['teachers'] = Teacher::orderBy('name', 'asc')->get();
    }

    public function onDelete()
    {
        $id = post('id');

        if (!$id) {
            return ['success' => false, 'message' => 'ID Kosong'];
        }

        $teacher = Teacher::find($id);
        if ($teacher) {
            $teacher->delete();
            return ['success' => true, 'message' => 'Data guru berhasil dihapus'];
        }

        return ['success' => false, 'message' => 'Data tidak ditemukan'];
    }
}