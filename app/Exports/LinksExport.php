<?php

namespace App\Exports;

use App\Models\Link;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LinksExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Link::with('post.tags')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            __('Title'),
            __('URL'),
            __('Content'),
            'Extra',
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
            $row->url,
            $row->content,
            $row->extra,
            $row->post->is_private ? __('Yes') : __('No'),
            $row->post->tags->pluck('name')->implode(', '),
            $row->created_at,
        ];
    }


}
