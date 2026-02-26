<?php 
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Winter\Storm\Exception\ValidationException;
class Cms69a01a9046c6b968613857_68c41376279c4e9aeb42db7ef818d18dClass extends Cms\Classes\PageCode
{
public function onStart()
{
    $this['studentsReady'] = Schema::hasTable('latihan_students');
    $this['student'] = (object) [
        'id' => null,
        'name' => '',
        'subject' => '',
        'is_active' => 1,
    ];
}
public function onSave()
{
    if (!Schema::hasTable('latihan_students')) {
        Flash::error('Tabel latihan_students belum tersedia.');
        return;
    }

    $data = [
        'name' => trim((string) post('name')),
        'subject' => trim((string) post('subject')),
        'is_active' => post('is_active') ? 1 : 0,
    ];

    $validator = Validator::make($data, [
        'name' => 'required|string|max:255|unique:latihan_students,name',
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

    Db::table('latihan_students')->insert([
        'name' => $data['name'],
        'subject' => $data['subject'],
        'is_active' => $data['is_active'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    Flash::success('{{flash_student_created}}');
    return Redirect::to($this->pageUrl('students'));
}
}
