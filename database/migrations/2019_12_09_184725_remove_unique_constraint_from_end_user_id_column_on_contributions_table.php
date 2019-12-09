<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueConstraintFromEndUserIdColumnOnContributionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contributions', function (Blueprint $table): void {
            $table->dropForeign(['end_user_id']);
            $table->dropUnique(['end_user_id']);
            $table->foreign('end_user_id')
                ->references('id')
                ->on('end_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->unique('end_user_id');
        });
    }
}
