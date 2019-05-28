<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table): void {
            $table->char('alpha_2')->primary();
            $table->string('name')->unique();
            $table->unsignedInteger('country_code')->unique();
        });

        $countriesPath = realpath(dirname(__DIR__)) . '/storage/countries.json';

        db()->table('countries')->insert(
            json_decode(file_get_contents($countriesPath), true)
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
}
