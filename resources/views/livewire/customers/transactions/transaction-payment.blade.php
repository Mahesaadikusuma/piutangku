<div>
    <flux:heading level="1" size="xl">Transaction Payment</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Piutang Transaction Payment</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard-customer')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('transactions.piutang.customer.index')">Transactions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Transaction Payment</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="my-5">
        <div class="space-y-5">
            <x-flash-message />
        </div>
    </div>

    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <form wire:submit='payment'>
            <div class="flex flex-col gap-5">
                @if ($transaction->paymentPiutangs->count() > 0)
                    <flux:heading level="2" size="lg">Piutang Transaction</flux:heading>
                    <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-700">
                            <tr>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Kode Transaction</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Kode piutang</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Customer Name</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Amount</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Created At</th>
                            {{-- <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Actions</th>
                            </tr> --}}
                        </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    @forelse ($transaction->paymentPiutangs as $key => $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            {{ $transaction->kode }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            {{ $payment->piutang->kode_piutang }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            {{ $transaction->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            {{ number_format($payment->amount) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            {{ Carbon\Carbon::parse($payment->created_at)->translatedFormat('d F Y') }}
                                        </td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            @if ($transaction->paymentPiutangs->count() > 0)
                                                <flux:button size="xs" variant="primary" class="cursor-pointer" :href="route('transaction.show',$transaction->id)">Show</flux:button>
                                            @endif
                                        </td> --}}
                                    </tr>
                                    @empty
                                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                        <td colspan="5" class="text-center text-gray-900 dark:text-white py-5">No Record Found</td>
                                    </tr>
                                    @endforelse
                            </tbody>
                        </table>
                    </div>
                @else   
                <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Kode Transaction</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Kode piutang</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Customer Name</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Amount</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Created At</th>
                        </tr>
                    </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    1.
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $transaction->kode }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $transaction->piutang->kode_piutang }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $transaction->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ number_format($transaction->transaction_total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
                <flux:input
                    wire:model.lazy="transactionTotal"
                    :label="__('Transaction Total')"
                    type="text"
                    autocomplete="transaction-total"
                    :placeholder="__('Transaction Total')"
                    disabled
                />
                <flux:button type="submit" class="w-full" variant="primary" color="indigo">Payment</flux:button>
            </div>
        </form>
        
    </div>
</div>
