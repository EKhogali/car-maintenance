<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\Tag;
use App\Models\Treasury;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Torgodly\Html2Media\Actions\Html2MediaAction;

class FinancialReport extends Page
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.financial-report';

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'report.financial.navigation';

    // Filter state (Livewire)
    public $from;
    public $to;
    public $account;
    public $type;
    public $tags = [];

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('from')->label(__('report.from'))->required(),
            DatePicker::make('to')->label(__('report.to'))->required(),

            Select::make('account')
                ->label(__('report.account'))
                ->options(fn() => Account::query()->orderBy('name')->pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),

            Select::make('type')
                ->label(__('report.type'))
                ->options([
                    'income' => __('report.income'),
                    'expense' => __('report.expense'),
                ])
                ->nullable(),

            // On a Page (no model), use options() not relationship()
            MultiSelect::make('tags')
                ->label(__('report.tags'))
                ->options(fn() => Tag::query()->orderBy('name')->pluck('name', 'id')->toArray())
                ->searchable(),
        ];
    }

    public function mount(): void
    {
        $this->form->fill([
            'from' => now()->startOfMonth(),
            'to' => now()->endOfMonth(),
        ]);
    }

    /** Submit handler to avoid calling render() recursively */
    public function filter(): void
    {
        // no-op; Livewire re-renders with updated state
    }

    /** Build the base query once so we can clone for sums/pagination */
    protected function baseQuery(): Builder
    {
        $from = $this->from ? Carbon::parse($this->from)->toDateString() : null;
        $to = $this->to ? Carbon::parse($this->to)->toDateString() : null;

        return Treasury::query()
            ->select(['id', 'transaction_date', 'account_id', 'type', 'amount'])
            ->when($from, fn($q) => $q->whereDate('transaction_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('transaction_date', '<=', $to))
            ->when($this->account, fn($q) => $q->where('account_id', $this->account))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->tags && is_array($this->tags), function ($q) {
                $q->whereHas('tags', fn($q2) => $q2->whereIn('tags.id', $this->tags));
            });
    }

    /** Paged rows for the table */
    protected function pagedTransactions()
    {
        return $this->baseQuery()
            ->with(['account', 'tags'])
            ->orderByDesc('transaction_date')
            ->get();
    }

    public function render(): View
    {
        // DB-side sums over the filtered set (fast + memory-safe)
        $base = $this->baseQuery();

        $totalIncome = (clone $base)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $base)->where('type', 'expense')->sum('amount');
        $net = $totalIncome - $totalExpense;

        $transactions = $this->transactions();
        

        return view(static::$view, compact('transactions', 'totalIncome', 'totalExpense', 'net'));
    }

    /** Header actions (Filament renders the button automatically) */
    protected function getHeaderActions(): array
    {
        return [
            Html2MediaAction::make('export')
                ->label(__('report.export'))
                ->content(function () {
                    // Use ALL filtered rows (no pagination) for the export
                    $base = $this->baseQuery()->with(['account', 'tags'])->orderBy('transaction_date');
                    $transactions = $base->get();

                    $totalIncome = (clone $this->baseQuery())->where('type', 'income')->sum('amount');
                    $totalExpense = (clone $this->baseQuery())->where('type', 'expense')->sum('amount');
                    $net = $totalIncome - $totalExpense;

                    return view('financial-report', [
                        'transactions' => $transactions,
                        'totalIncome' => $totalIncome,
                        'totalExpense' => $totalExpense,
                        'net' => $net,
                        'from' => $this->from ? Carbon::parse($this->from) : null,
                        'to' => $this->to ? Carbon::parse($this->to) : null,
                    ]);
                })
                ->savePdf()
                ->filename(fn(): string => sprintf(
                    'financial_report_%s_%s',
                    $this->from ? Carbon::parse($this->from)->format('Ymd') : 'no-from',
                    $this->to ? Carbon::parse($this->to)->format('Ymd') : 'no-to',
                ))
            ,
        ];
    }
}
