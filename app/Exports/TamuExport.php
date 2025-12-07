<?php

namespace App\Exports;

use App\Models\Tamu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TamuExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function collection()
    {
        return Tamu::with(['divisi', 'visitStatus'])
            ->whereDate('created_at', '>=', $this->start)
            ->whereDate('created_at', '<=', $this->end)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Instansi',
            'No HP',
            'Divisi',
            'Status',
            'Keperluan',
            'Tanggal Dibuat'
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama,
            $row->instansi,
            $row->no_hp,
            $row->divisi->nama_divisi ?? '-',
            $row->visitStatus->status ?? '-',
            $row->keperluan,
            $row->created_at->format('Y-m-d H:i'),
        ];
    }
}
