<?php

declare(strict_types=1);

namespace App\Models\Mutators;

trait EndUserMutators
{
    /**
     * @param string|null $country
     * @return string|null
     */
    public function getCountryAttribute(?string $country): ?string
    {
        return is_string($country) ? decrypt($country) : null;
    }

    /**
     * @param string|null $country
     */
    public function setCountryAttribute(?string $country): void
    {
        $this->attributes['country'] = is_string($country)
            ? encrypt($country)
            : null;
    }

    /**
     * @param string|null $birthYear
     * @return int|null
     */
    public function getBirthYearAttribute(?string $birthYear): ?int
    {
        return is_string($birthYear) ? decrypt($birthYear) : null;
    }

    /**
     * @param int|null $birthYear
     */
    public function setBirthYearAttribute(?int $birthYear): void
    {
        $this->attributes['birth_year'] = is_int($birthYear)
            ? encrypt($birthYear)
            : null;
    }

    /**
     * @param string|null $gender
     * @return string|null
     */
    public function getGenderAttribute(?string $gender): ?string
    {
        return is_string($gender) ? decrypt($gender) : null;
    }

    /**
     * @param string|null $gender
     */
    public function setGenderAttribute(?string $gender): void
    {
        $this->attributes['gender'] = is_string($gender)
            ? encrypt($gender)
            : null;
    }

    /**
     * @param string|null $ethnicity
     * @return string|null
     */
    public function getEthnicityAttribute(?string $ethnicity): ?string
    {
        return is_string($ethnicity) ? decrypt($ethnicity) : null;
    }

    /**
     * @param string|null $ethnicity
     */
    public function setEthnicityAttribute(?string $ethnicity): void
    {
        $this->attributes['ethnicity'] = is_string($ethnicity)
            ? encrypt($ethnicity)
            : null;
    }
}
