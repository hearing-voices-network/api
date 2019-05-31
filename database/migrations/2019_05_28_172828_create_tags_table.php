<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('parent_tag_id')->nullable();
            $table->string('name');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });

        Schema::table('tags', function (Blueprint $table): void {
            $table->foreign('parent_tag_id')->references('id')->on('tags');
        });

        db()->table('tags')->insert($this->getTags());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }

    /**
     * @return array
     */
    protected function getTags(): array
    {
        // Get the path of the tags JSON file.
        $tagsPath = realpath(dirname(__DIR__)) . '/storage/tags.json';

        // Decode the JSON file into an associative array.
        $tags = json_decode(file_get_contents($tagsPath), true);

        /*
         * Prepare the empty parsed tags array, as the JSON object is not in the format matching
         * the database.
         */
        $parsedTags = [];

        // Loop through each parent tag.
        foreach ($tags as $parent => $children) {
            // Parse the tag into a format that can be inserted into the database.
            $parentTag = [
                'id' => Str::uuid()->toString(),
                'parent_tag_id' => null,
                'name' => $parent,
            ];
            $parsedTags[] = $parentTag;

            // Loop through each child tag of the current parent.
            foreach ($children as $child) {
                /*
                 * Parse the tag into a format that can be inserted into the database, and link to
                 * the parent.
                 */
                $parsedTags[] = [
                    'id' => Str::uuid()->toString(),
                    'parent_tag_id' => $parentTag['id'],
                    'name' => $child,
                ];
            }
        }

        return $parsedTags;
    }
}
