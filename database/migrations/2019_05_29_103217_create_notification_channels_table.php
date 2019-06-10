<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNotificationChannelsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_channels', function (Blueprint $table): void {
            $table->string('channel')->primary();
        });

        $notificationChannelsPath = realpath(dirname(__DIR__)) . '/storage/notification_channels.json';

        DB::table('notification_channels')->insert(
            json_decode(file_get_contents($notificationChannelsPath), true)
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_channels');
    }
}
