<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailActive implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = substr(strrchr($value, "@"), 1);

        if (!checkdnsrr($domain, "MX")) {
            $fail("Domain email tidak valid atau tidak aktif.");
        }
    }
}
