<?php

namespace App\Imports;

use App\Helpers\ArrayKeyCaseConverter;
use App\Helpers\NameSplitter;
use Carbon\Carbon;
use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountsImport
{


    /**
     * Check for valid Account data and store data.
     *
     * @param string $data
     *
     * @return void
     */
    public function create(array $data)
    {
        $data = (new ArrayKeyCaseConverter)->snakeCase($data);

        DB::transaction(function () use ($data) {
            try {
                foreach ($data as $row) {
                    $birthday = $this->checkAge($row['date_of_birth']);
                    if ($birthday !== false) {
                        $row['date_of_birth'] = $birthday;
                        $row['checked'] = filter_var($row['checked'], FILTER_VALIDATE_BOOLEAN);
                        $row = $row + NameSplitter::fullName($row['name']);

                        $validation = $this->validateData($row);
                        if (!$validation->passes()) {
                            Log::channel('validation')->debug("Validation:", ['errors' => $validation->errors(), 'record' => $row]);
                        } else {
                            $this->insertAccount($row);
                        }
                    } else {
                        Log::channel('skipimport')->info('birthdayFalse', $row);
                    }
                }
            } catch (Exception $e) {
                Log::channel('validation')->debug('import:', ['insert' => $e]);
            }
        });
    }

    /**
     * Create validation.
     *
     * @param array $array
     *
     * @return Validator
     */
    public function validateData(array $data)
    {
        $rules = [
            'name' => 'required|string',
            'salutation' => 'nullable|string',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'suffix' => 'nullable|string',
            'address' => 'string',
            'checked' => 'required|boolean',
            'description' => 'required|string',
            'interest' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ];

        return (Validator::make($data, $rules));
    }


    /**
     * create Account.
     *
     * @return Account
     */
    protected function insertAccount(array $data)
    {
        $account = Account::create($data);
        if (!empty($data['credit_card'])) {
            $account->creditcards()->create($data['credit_card']);
        }
        return $account;
    }

    /**
     * create a Carbon object.
     *
     * @param string $date
     *
     * @return Carbon
     */
    public function createProperDate(string $date)
    {
        if (Carbon::hasFormatWithModifiers($date, 'd#m#Y!')) {
            return Carbon::createFromFormat('d/m/Y', $date);
        } elseif (Carbon::hasFormatWithModifiers($date, 'Y#m#d!')) {
            return Carbon::createFromFormat('Y-m-d', $date);
        }
        return new Carbon($date);
    }

    /**
     * create a Carbon object.
     *
     * @param string $date
     * @param integer $minAge
     * @param integer $maxAge
     *
     * @return string|null
     */
    public function checkAge(?string $date, $minage = 18, $maxAge = 65)
    {
        if ($date === null) {
            return $date;
        }
        $properDate = $this->createProperDate($date);
        return ($properDate->age >= $minage && $properDate->age <= $maxAge ? $properDate->format('Y-m-d') : false);
    }
}
