<?php

namespace App\Exports;

use App\Models\Chest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ChestsExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Chest::with('post.tags')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            __('Title'),
            __('Content'),
            __('Tags'),
            __('Date'),
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->title,
            $this->transformContent($row->content),
            $row->post->tags->pluck('name')->implode(', '),
            $row->created_at,
        ];
    }

    public function transformContent(array $content): string
    {
        $output = '';

        foreach ($content as $line) {
            if ($line->type === 'code') {
                $output .= $line->name . " :\n" . $line->value . "\n";
            } else {
                $output .= $line->name . " : " . $line->value . "\n";
            }
        }

        return $output;
    }
}
