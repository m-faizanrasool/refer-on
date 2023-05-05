<?php

namespace App\Rules;

use App\Models\Brand;
use Illuminate\Contracts\Validation\Rule;

class BrandUniqueRule implements Rule
{
    protected $country_id;

    public function __construct($country_id)
    {
        $this->country_id = $country_id;
    }

    public function passes($attribute, $value)
    {
        $exists = Brand::where('country_id', $this->country_id)->where('name', $value)->exists();

        return !$exists;
    }

    public function message()
    {
        return 'The :attribute name already exists for this country.';
    }
}
