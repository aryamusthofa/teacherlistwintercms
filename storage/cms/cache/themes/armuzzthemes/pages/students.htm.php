<?php 
use Illuminate\Support\Facades\Schema;
class Cms699ffb549c1cb107155304_a4e8fa9cadee1af97dd1b5bcd6d8ca34Class extends Cms\Classes\PageCode
{
public function loadStudents()
{
    $this['studentsReady'] = Schema::hasTable('latihan_students');
    $filters = [
        'q' => trim((string) Input::get('q', '')),
        'status' => (string) Input::get('status', 'all'),
        'page' => max(1, (int) Input::get('page', 1)),
    ];

    if (!$this['studentsReady']) {
        $this['students'] = [];
        $this['pager'] = ['current' => 1, 'last' => 1, 'total' => 0, 'perPage' => 10];
        $this['filters'] = $filters;
        return;
    }

    $perPage = 10;
    $baseQuery = Db::table('latihan_students');

    if ($filters['q'] !== '') {
        $q = $filters['q'];
        $baseQuery->where(function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('subject', 'like', "%{$q}%");
        });
    }

    if ($filters['status'] === 'active') {
        $baseQuery->where('is_active', 1);
    } elseif ($filters['status'] === 'inactive') {
        $baseQuery->where('is_active', 0);
    } else {
        $filters['status'] = 'all';
    }

    $total = (clone $baseQuery)->count();
    $last = max(1, (int) ceil($total / $perPage));
    $page = min($filters['page'], $last);
    $filters['page'] = $page;
    $this['filters'] = $filters;

    $items = (clone $baseQuery)
        ->orderByDesc('id')
        ->offset(($page - 1) * $perPage)
        ->limit($perPage)
        ->get();

    $this['students'] = $items;
    $this['pager'] = [
        'current' => $page,
        'last' => $last,
        'total' => $total,
        'perPage' => $perPage,
    ];
}
public function onStart()
{
    $this->loadStudents();
}
public function onDelete()
{
    if (!Schema::hasTable('latihan_students')) {
        Flash::error('Tabel latihan_students belum tersedia.');
        return;
    }

    $id = (int) post('id');
    if ($id <= 0) {
        Flash::error('ID tidak valid.');
        return;
    }

    Db::table('latihan_students')->where('id', $id)->delete();
    Flash::success('{{flash_student_deleted}}');
    $this->loadStudents();
}
public function onToggleActive()
{
    if (!Schema::hasTable('latihan_students')) {
        Flash::error('Tabel latihan_students belum tersedia.');
        return;
    }

    $id = (int) post('id');
    if ($id <= 0) {
        Flash::error('ID tidak valid.');
        return;
    }

    $student = Db::table('latihan_students')->where('id', $id)->first();
    if (!$student) {
        Flash::error('Student tidak ditemukan.');
        return;
    }

    Db::table('latihan_students')->where('id', $id)->update([
        'is_active' => $student->is_active ? 0 : 1,
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    Flash::success('{{flash_student_status_changed}}');
    $this->loadStudents();
}
}
