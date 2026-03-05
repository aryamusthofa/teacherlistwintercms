<?php namespace Latihan\Latihan\Updates;

use Illuminate\Support\Facades\Schema;
use Winter\Storm\Database\Updates\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIsVerifiedToTeachersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('latihan_teachers', 'is_verified')) {
            Schema::table('latihan_teachers', function (Blueprint $table) {
                $table->boolean('is_verified')->default(false)->after('is_active');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('latihan_teachers', 'is_verified')) {
            Schema::table('latihan_teachers', function (Blueprint $table) {
                $table->dropColumn('is_verified');
            });
        }
    }
}
