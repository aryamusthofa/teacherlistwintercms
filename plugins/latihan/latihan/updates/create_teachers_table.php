<?php namespace Latihan\Latihan\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Winter\Storm\Database\Updates\Migration;
use Illuminate\Database\Schema\Blueprint;
class CreateTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('latihan_teachers', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('subject');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('latihan_teachers')->insert([
            [
                'name'       => 'Armuzz Dev Misteriously',
                'subject'    => 'IT Engineering',
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Armuzz Second Teacher',
                'subject'    => 'King Of The Technologia',
                'is_active'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('latihan_teachers');
    }
}
