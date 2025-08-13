{{-- Interactive page (no recursive render calls, paginated table) --}}
<x-filament::page>
    <form wire:submit.prevent="filter" class="space-y-6">
        <div class="grid grid-cols-4 gap-4">
            {{ $this->form }}

            <div class="col-span-4 flex space-x-2">
                <x-filament::button type="submit">
                    {{ __('filament::buttons.filter') }}
                </x-filament::button>

                {{-- Header export action is rendered automatically by Filament --}}
            </div>
        </div>
    </form>

    {{-- Summary cards --}}
    <div class="grid grid-cols-3 gap-6 mt-8">
        <x-filament::card>
            <h2 class="text-sm font-medium">{{ __('report.income') }}</h2>
            <p class="text-3xl font-bold">{{ number_format($totalIncome, 2) }}</p>
        </x-filament::card>
        <x-filament::card>
            <h2 class="text-sm font-medium">{{ __('report.expense') }}</h2>
            <p class="text-3xl font-bold">{{ number_format($totalExpense, 2) }}</p>
        </x-filament::card>
        <x-filament::card>
            <h2 class="text-sm font-medium">{{ __('report.net') }}</h2>
            <p class="text-3xl font-bold">{{ number_format($net, 2) }}</p>
        </x-filament::card>
    </div>

    {{-- Transactions table (paginated) --}}
    <div class="overflow-x-auto mt-8">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">{{ __('report.date') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">{{ __('report.account') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">{{ __('report.type') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">{{ __('report.amount') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">{{ __('report.tags') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($transactions as $tx)
                    <tr>
                        <td class="px-4 py-2">{{ optional($tx->transaction_date)->format('Y-m-d') ?? '' }}</td>
                        <td class="px-4 py-2">{{ $tx->account->name ?? '' }}</td>
                        <td class="px-4 py-2">{{ __("report.{$tx->type}") }}</td>
                        <td class="px-4 py-2">{{ number_format($tx->amount, 2) }}</td>
                        <td class="px-4 py-2">{{ $tx->tags->pluck('name')->join(', ') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            {{ __('filament::pages/empty-state.title') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-filament::page>