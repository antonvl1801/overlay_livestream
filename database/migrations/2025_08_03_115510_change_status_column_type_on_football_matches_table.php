<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('football_matches', function (Blueprint $table) {
            // Đầu tiên cần drop cột enum cũ
            $table->dropColumn('status');
        });

        Schema::table('football_matches', function (Blueprint $table) {
            // Sau đó tạo lại cột với kiểu integer
            $table->integer('status')->default(0)->after('started_at');
        });
    }

    public function down()
    {
        Schema::table('football_matches', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('football_matches', function (Blueprint $table) {
            $table->enum('status', [0, 1, 99])->default(0)->after('started_at');
        });
    }
};
