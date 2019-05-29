<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('end_users', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
            $table->char('country', 2)->nullable();
            $table->foreign('country')->references('alpha_2')->on('countries');
            $table->unsignedInteger('birth_year')->nullable();
            $table->string('gender')->nullable();
            $table->string('ethnicity')->nullable();
            $table->timestamp('gdpr_consented_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_users');
    }
}
