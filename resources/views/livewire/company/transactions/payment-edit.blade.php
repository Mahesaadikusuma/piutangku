<div>
    <flux:heading level="1" size="xl">Payment Edit</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Payment Edit</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('transaction.index')">Transactions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Payment Edit</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <flux:separator variant="subtle" class="mb-6" />


    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="my-5">
        <div class="space-y-5 relative">
            <button @click="show = false" class="absolute top-0 right-0 px-2 py-1 text-xl text-gray-600 hover:text-black">&times;</button>
        </div>
    </div>
    
    <x-action-message class="me-3 mb-4" on="payment-updated">
        <div class="bg-teal-50 border-t-2 border-teal-500 rounded-lg p-4 dark:bg-teal-800/30" role="alert" tabindex="-1" aria-labelledby="hs-bordered-success-style-label">
            <div class="flex">
              <div class="shrink-0">
                <!-- Icon -->
                <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-400">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                    <path d="m9 12 2 2 4-4"></path>
                  </svg>
                </span>
                <!-- End Icon -->
              </div>
              <div class="ms-3">
                <h3 id="hs-bordered-success-style-label" class="text-gray-800 font-semibold dark:text-white">
                  Success
                </h3>
                <p class="text-sm text-gray-700 dark:text-neutral-400">
                    {{ __('Transaction Update Success.') }}
                </p>
              </div>
            </div>
        </div>
    </x-action-message>

    <x-action-message on="error">
        <div class="bg-red-50 border-s-4 border-red-500 p-4 dark:bg-red-800/30" role="alert" tabindex="-1" aria-labelledby="hs-bordered-red-style-label">
            <div class="flex">
              <div class="shrink-0">
                <!-- Icon -->
                <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-red-100 bg-red-200 text-red-800 dark:border-red-900 dark:bg-red-800 dark:text-red-400">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                  </svg>
                </span>
                <!-- End Icon -->
              </div>
              <div class="ms-3">
                <h3 id="hs-bordered-red-style-label" class="text-gray-800 font-semibold dark:text-white">
                  Error
                </h3>
                <p class="text-sm text-gray-700 dark:text-neutral-400">
                    {{ __($error) }}
                </p>
              </div>
            </div>
          </div>
    </x-action-message>
    
    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <form wire:submit='update'>
            <div class="flex flex-col gap-5">
                @if ($transaction->paymentPiutangs->count() > 0)
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                      <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                          <thead class="bg-gray-50 dark:bg-neutral-700">
                            <tr>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Kode Piutang</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Kode Transaction</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Customer Name</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Total Piutang</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Sisa Hutang</th>
                              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Amount Payment</th>
  
                            </tr>
                          </thead>
                          <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                              @foreach ($transaction->paymentPiutangs as $index => $payment)
                                  <tr>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                        {{ $index + 1 }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                        {{ $payment->piutang->kode_piutang }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                        {{ $transaction->kode }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                          {{ $transaction->user->name }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                          {{ number_format($payment->piutang->jumlah_piutang) }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                          {{ number_format($payment->piutang->sisa_piutang) }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                          {{ number_format($payment->amount) }}
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                @endif
                

                <div x-data="{ value: @entangle('transactionTotal') }">
                    <flux:input
                        readonly
                        :label="__('Transaction Total')"
                        type="text"
                        x-bind:value="new Intl.NumberFormat('id-ID').format(value ?? 0)"
                        autocomplete="off"
                    />
                </div>

                <flux:select wire:model="status" placeholder="Pilih Status..." label="Pilih Status" description="Status Pembayaran...">
                    @foreach (\App\Enums\StatusType::cases() as $status)
                        <flux:select.option :value="$status->value">{{ $status->value }}</flux:select.option>
                    @endforeach
                </flux:select>
                
                <flux:input
                    wire:model.lazy="jenisPembayaran"
                    :label="__('Jenis Pembayaran')"
                    type="text"
                    required
                    autocomplete="jenis-pembayaran"
                    :placeholder="__('Jenis Pembayaran')"
                />

                <flux:input type="file" wire:model="proof" label="Bukti Pembayaran"/>

                <flux:button type="submit" variant="primary" class="w-full">
                    Save
                </flux:button>  

                
            </div>
        </form>
    </div>
</div>
