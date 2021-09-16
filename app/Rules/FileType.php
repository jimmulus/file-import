<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileType implements Rule
{
    private $file;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        return $this->file = $file;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $extension = strtolower($this->file->getClientOriginalExtension());

        return in_array($extension, ['json', 'csv', 'xls', 'xlsx']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The file must be a file of type: json, csv, xls, xlsx.';
    }
}
