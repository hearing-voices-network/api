<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('end_user_id')->unique();
            $table->foreign('end_user_id')->references('id')->on('end_users');
            $table->text('content');
            $table->string('status');
            $table->foreign('status')->references('status')->on('contribution_statuses');
            $table->text('changes_requested')->nullable();
            $table->timestamp('status_last_updated_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
}
