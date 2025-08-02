<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('football_matches', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('home_team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('away_team_id')->constrained('teams')->onDelete('cascade');
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
            $table->time('live_clock')->nullable();
            $table->timestamp('live_clock_started_at')->nullable();
            $table->enum('status', ['scheduled', 'live', 'finished'])->default('scheduled');
            $table->enum('match_status', ['stop', 'ongoing'])->default('stop');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
