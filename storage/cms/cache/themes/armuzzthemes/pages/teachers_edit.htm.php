<?php 
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Winter\Storm\Exception\ValidationException;
class Cms6996e3ee8a399689507546_0dfef302779f9639ae46c54c3d26ae0bClass extends Cms\Classes\PageCode
{
public function onStart()
{
    $this['teachersReady'] = Schema::hasTable('latihan_teachers');
    if (!$this['teachersReady']) {
        $this['teacher'] = null;
        return;
    }

    $id = (int) $this->param('id');
    $teacher = Db::table('latihan_teachers')->where('id', $id)->first();
    if (!$teacher) {
        Flash::error('Teacher tidak ditemukan.');
        return Redirect::to($this->pageUrl('teachers'));
    }

    $this['teacher'] = $teacher;
}
public function onSave()
{
    if (!Schema::hasTable('latihan_teachers')) {
        Flash::error('Tabel latihan_teachers belum tersedia.');
        return;
    }

    $id = (int) $this->param('id');
    $teacher = Db::table('latihan_teachers')->where('id', $id)->first();
    if (!$teacher) {
        Flash::error('Teacher tidak ditemukan.');
        return;
    }

    $data = [
        'name' => trim((string) post('name')),
        'subject' => trim((string) post('subject')),
        'is_active' => post('is_active') ? 1 : 0,
    ];

    $validator = Validator::make($data, [
        'name' => 'required|string|max:255|unique:latihan_teachers,name,'.$id,
        'subject' => 'required|string|max:255',
        'is_active' => 'boolean',
    ], [
        'name.required' => '{{name_required}}',
        'name.unique' => '{{name_duplicate}}',
        'subject.required' => '{{subject_required}}',
    ]);

    if ($validator->fails()) {
        throw new ValidationException($validator);
    }

    Db::table('latihan_teachers')->where('id', $id)->update([
        'name' => $data['name'],
        'subject' => $data['subject'],
        'is_active' => $data['is_active'],
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    Flash::success('{{flash_teacher_saved}}');
}
}
