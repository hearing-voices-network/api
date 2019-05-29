<?php

declare(strict_types=1);

namespace App\Models;

class Country extends BaseModel
{
    use Mutators\CountryMutators;
    use Relationships\CountryRelationships;
    use Scopes\CountryScopes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'alpha_2';
}
