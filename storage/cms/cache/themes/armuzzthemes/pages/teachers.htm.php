<?php 
use Illuminate\Support\Facades\Schema;
class Cms698d5e20cbadb020689639_8a396c84149d60bce6a90087032c4bf5Class extends Cms\Classes\PageCode
{
public function loadTeachers()
{
    $this['teachersReady'] = Schema::hasTable('latihan_teachers');
    $filters = [
        'q' => trim((string) Input::get('q', '')),
        'status' => (string) Input::get('status', 'all'),
        'page' => max(1, (int) Input::get('page', 1)),
    ];

    if (!$this['teachersReady']) {
        $this['teachers'] = [];
        $this['pager'] = ['current' => 1, 'last' => 1, 'total' => 0, 'perPage' => 10];
        $this['filters'] = $filters;
        return;
    }

    $perPage = 10;
    $baseQuery = Db::table('latihan_teachers');

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

    $this['teachers'] = $items;
    $this['pager'] = [
        'current' => $page,
        'last' => $last,
        'total' => $total,
        'perPage' => $perPage,
    ];
}
public function onStart()
{
    $this->loadTeachers();
}
public function onDelete()
{
    if (!Schema::hasTable('latihan_teachers')) {
        Flash::error('Tabel latihan_teachers belum tersedia.');
        return;
    }

    $id = (int) post('id');
    if ($id <= 0) {
        Flash::error('ID tidak valid.');
        return;
    }

    Db::table('latihan_teachers')->where('id', $id)->delete();
    Flash::success('{{flash_teacher_deleted}}');
}
public function onToggleActive()
{
    if (!Schema::hasTable('latihan_teachers')) {
        Flash::error('Tabel latihan_teachers belum tersedia.');
        return;
    }

    $id = (int) post('id');
    if ($id <= 0) {
        Flash::error('ID tidak valid.');
        return;
    }

    $teacher = Db::table('latihan_teachers')->where('id', $id)->first();
    if (!$teacher) {
        Flash::error('Teacher tidak ditemukan.');
        return;
    }

    Db::table('latihan_teachers')->where('id', $id)->update([
        'is_active' => $teacher->is_active ? 0 : 1,
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    Flash::success('{{flash_status_changed}}');
}
}
