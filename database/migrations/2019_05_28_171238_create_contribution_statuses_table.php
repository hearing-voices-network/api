<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContributionStatusesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contribution_statuses', function (Blueprint $table): void {
            $table->string('status')->primary();
        });

        $contributionStatusesPath = realpath(dirname(__DIR__)) . '/storage/contribution_statuses.json';

        db()->table('contribution_statuses')->insert(
            json_decode(file_get_contents($contributionStatusesPath), true)
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribution_statuses');
    }
}
