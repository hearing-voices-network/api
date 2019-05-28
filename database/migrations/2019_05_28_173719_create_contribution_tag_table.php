<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionTagTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contribution_tag', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('contribution_id');
            $table->foreign('contribution_id')->references('id')->on('contributions');
            $table->uuid('tag_id');
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->unique(['contribution_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribution_tag');
    }
}
