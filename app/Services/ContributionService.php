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
            'status_last_updated_at' => Date::now(),
        ]);

        $contribution->tags()->sync($data['tags']);

        return $contribution;
    }

    /**
     * @param \App\Models\Contribution $contribution
     * @param array $data
     * @return \App\Models\Contribution
     */
    public function update(Contribution $contribution, array $data): Contribution
    {
        /*
         * The status logic goes as follows:
         * If the status is "public", then change it to "in review".
         * If the status is "private", then leave it as "private".
         * If the status is  "in review", then leave it as "in review".
         * If the status is "changes requested", then change it to "in review".
         */
        $status = $data['status'] ?? $contribution->status;

        switch ($status) {
            case Contribution::STATUS_PUBLIC:
            case Contribution::STATUS_CHANGES_REQUESTED:
                $status = Contribution::STATUS_IN_REVIEW;
                break;
        }

        /** @var \App\Models\Contribution $contribution */
        $contribution->update([
            'content' => $this->markdown->sanitise($data['content'] ?? $contribution->content),
            'status' => $status,
            'status_last_updated_at' => Date::now(),
        ]);

        if (isset($data['tags'])) {
            $contribution->tags()->sync($data['tags']);
        }

        return $contribution;
    }
}
