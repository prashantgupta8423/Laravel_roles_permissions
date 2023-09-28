<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::all();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone'];
    }

    public function map($student): array
    {
        return [
            $student->name,
            $student->email,
            $student->phone,
        ];
    }
}
