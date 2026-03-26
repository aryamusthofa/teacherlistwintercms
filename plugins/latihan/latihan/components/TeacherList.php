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
        $search = trim((string) get('search'));
        $status = get('status');
        $perPage = 5;

        $query = Teacher::orderBy('name', 'asc');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            if ($status === 'active') {
                $query->where('is_active', 1);
            } elseif ($status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        $teachers = $query->paginate($perPage);
        $teachers->appends([
            'search' => $search,
            'status' => $status,
        ]);

        $this->page['teachers'] = $teachers;
        $this->page['search'] = $search;
        $this->page['status'] = $status;
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