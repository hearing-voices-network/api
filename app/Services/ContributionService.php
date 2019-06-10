<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contribution;
use App\Support\Markdown;
use Illuminate\Support\Facades\Date;

class ContributionService
{
    /**
     * @var \App\Support\Markdown
     */
    protected $markdown;

    /**
     * ContributionService constructor.
     *
     * @param \App\Support\Markdown $markdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * @param array $data
     * @return \App\Models\Contribution
     */
    public function create(array $data): Contribution
    {
        /** @var \App\Models\Contribution $contribution */
        $contribution = Contribution::create([
            'end_user_id' => $data['end_user_id'],
            'content' => $this->markdown->sanitise($data['content']),
            'status' => $data['status'],
            'status_last_updated_at' => $data['status_last_updated_at'] ?? Date::now(),
        ]);

        $contribution->tags()->sync($data['tags']);

        return $contribution;
    }
}
