<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailActive implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Ambil domain setelah "@"
        $domain = substr(strrchr($value, "@"), 1);

        // Cek format domain: minimal harus ada "." misal "gmail.com"
        if (!preg_match('/\./', $domain)) {
            $fail("Domain email tidak valid. Harus ada titik setelah '@'.");
            return;
        }

        // Cek apakah domain aktif dengan DNS MX
        if (!checkdnsrr($domain, "MX")) {
            $fail("Domain email tidak valid atau tidak aktif.");
            return;
        }
    }
}
