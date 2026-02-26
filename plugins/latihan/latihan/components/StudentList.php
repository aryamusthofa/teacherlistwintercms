<?php namespace Latihan\Latihan\Components;

use Cms\Classes\ComponentBase;
use Latihan\Latihan\Models\Student;

class StudentList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Student List',
            'description' => 'Menampilkan daftar siswa dari database'
        ];
    }

    public function onRun()
    {
        $this->page['students'] = Student::orderBy('name', 'asc')->get();
    }

    public function onDelete()
    {
        $id = post('id');

        if (!$id) {
            return ['success' => false, 'message' => 'ID Kosong'];
        }

        $student = Student::find($id);
        if ($student) {
            $student->delete();
            return ['success' => true, 'message' => 'Data siswa berhasil dihapus'];
        }

        return ['success' => false, 'message' => 'Data tidak ditemukan'];
    }
}
