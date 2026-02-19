<?php 
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Winter\Storm\Exception\ValidationException;
class Cms6996e2dbbc5cc622919176_5c6731b94bc1ccb1c6505a8be8252b49Class extends Cms\Classes\PageCode
{
public function onStart()
{
    $this['teachersReady'] = Schema::hasTable('latihan_teachers');
    $this['teacher'] = (object) [
        'id' => null,
        'name' => '',
        'subject' => '',
        'is_active' => 1,
    ];
}
public function onSave()
{
    if (!Schema::hasTable('latihan_teachers')) {
        Flash::error('Tabel latihan_teachers belum tersedia.');
        return;
    }

    $data = [
        'name' => trim((string) post('name')),
        'subject' => trim((string) post('subject')),
        'is_active' => post('is_active') ? 1 : 0,
    ];

    $validator = Validator::make($data, [
        'name' => 'required|string|max:255|unique:latihan_teachers,name',
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

    Db::table('latihan_teachers')->insert([
        'name' => $data['name'],
        'subject' => $data['subject'],
        'is_active' => $data['is_active'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    Flash::success('{{flash_teacher_created}}');
    return Redirect::to($this->pageUrl('teachers'));
}
}
