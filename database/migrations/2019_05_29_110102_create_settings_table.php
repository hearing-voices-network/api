<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->string('key');
            $table->json('value');
        });

        $settingsPath = realpath(dirname(__DIR__)) . '/storage/settings.json';
        $settings = json_decode(file_get_contents($settingsPath), true);
        foreach ($settings as &$setting) {
            $setting['value'] = json_encode($setting['value']);
        }

        DB::table('settings')->insert($settings);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
}
