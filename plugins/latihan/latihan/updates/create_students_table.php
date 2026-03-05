<?php namespace Latihan\Latihan\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Winter\Storm\Database\Updates\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('latihan_students', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('subject');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('latihan_students')->insert([
            [
                'name'       => 'Beryl',
                'subject'    => 'Matematika',
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Elora',
                'subject'    => 'Bahasa Indonesia',
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Ghaly',
                'subject'    => 'Fisika',
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Rina Mulyani',
                'subject'    => 'Kimia',
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Nabila Kusuma',
                'subject'    => 'Biologi',
                'is_active'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('latihan_students');
    }
}
