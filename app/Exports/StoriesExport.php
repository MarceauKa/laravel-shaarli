<?php

namespace App\Exports;

use App\Models\Story;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StoriesExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Story::with('post.tags')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            __('Title'),
            'Slug',
            __('Content'),
            __('Is private?'),
            __('Tags'),
            __('Date'),
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->title,
            $row->slug,
            $row->content,
            $row->post->is_private ? __('Yes') : __('No'),
            $row->post->tags->pluck('name')->implode(', '),
            $row->created_at,
        ];
    }


}
