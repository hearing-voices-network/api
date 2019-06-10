<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAuditActionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_actions', function (Blueprint $table): void {
            $table->string('action')->primary();
        });

        $auditActionsPath = realpath(dirname(__DIR__)) . '/storage/audit_actions.json';

        DB::table('audit_actions')->insert(
            json_decode(file_get_contents($auditActionsPath), true)
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_actions');
    }
}
