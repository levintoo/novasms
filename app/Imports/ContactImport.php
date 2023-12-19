<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContactImport implements ToModel, WithHeadingRow, WithValidation, withChunkReading, WithBatchInserts
{
    private int $userId;
    private int $groupId;

    public function __construct($groupId, $userId)
    {
        $this->userId = $userId;

        $this->groupId = $groupId;
    }

    /**
     * @param array $row
     *
     * @return Contact
     */
    public function model(array $row): Contact
    {
        return new Contact([
            'first_name' => $row['first_name'],

            'last_name' => $row['last_name'],

            'phone' => $row['phone'],

            'user_id' => $this->userId,

            'group_id' => $this->groupId,
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|max:255',

            'last_name' => 'required|max:255',

            'phone' => 'required|max:255',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
