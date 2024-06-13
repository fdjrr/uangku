<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $pemasukan   = Transaction::query()->whereBetween('date_transaction', [$startDate, $endDate])->incomes()->sum('amount');
        $pengeluaran = Transaction::query()->whereBetween('date_transaction', [$startDate, $endDate])->expenses()->sum('amount');

        return [
            Stat::make('Total Pemasukan', number_format($pemasukan, 0, '.', '.')),
            Stat::make('Total Pengeluaran', number_format($pengeluaran, 0, '.', '.')),
            Stat::make('Selisih', number_format($pemasukan - $pengeluaran, 0, '.', '.')),
        ];
    }
}
