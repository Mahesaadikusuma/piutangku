<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                <div class="p-4 md:p-5 flex justify-between gap-x-3">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                            Total users
                        </p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data['totalUsers'] }}
                            </h3>
                        </div>
                    </div>
                    <div
                        class="shrink-0 flex justify-center items-center size-11 bg-blue-600 text-white rounded-full dark:bg-blue-900 dark:text-blue-200">
                        <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                </div>

                
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div
                class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                <div class="p-4 md:p-5 flex justify-between gap-x-3">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                            Transactions
                        </p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="mt-1 text-xl font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data['totalTransactions'] }}
                            </h3>
                        </div>
                    </div>
                    <div
                        class="shrink-0 flex justify-center items-center size-11 bg-blue-600 text-white rounded-full dark:bg-blue-900 dark:text-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </div>
                </div>

                
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div
                class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                <div class="p-4 md:p-5 flex justify-between gap-x-3">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                            Piutangs
                        </p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data['countPiutang'] }}
                            </h3>
                        </div>
                    </div>
                    <div
                        class="shrink-0 flex justify-center items-center size-11 bg-blue-600 text-white rounded-full dark:bg-blue-900 dark:text-blue-200">
                        <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" />
                            <path d="m12 12 4 10 1.7-4.3L22 16Z" />
                        </svg>
                    </div>
                </div>

                
            </div>
            <!-- End Card -->
            <div
                class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                <div class="p-4 md:p-5 flex justify-between gap-x-3">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                            Total Tagihan Piutang Keseluruhan 
                        </p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data['totalPiutang'] }}
                            </h3>
                        </div>
                    </div>
                    <div
                        class="shrink-0 flex justify-center items-center size-11 bg-blue-600 text-white rounded-full dark:bg-blue-900 dark:text-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>      
                    </div>
                </div>

                
            </div>
            <!-- End Card -->
            <div
                class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                <div class="p-4 md:p-5 flex justify-between gap-x-3">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                            Total Piutang Terbayar 
                        </p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data['totalSisaPiutang'] }}
                            </h3>
                        </div>
                    </div>
                    <div
                        class="shrink-0 flex justify-center items-center size-11 bg-blue-600 text-white rounded-full dark:bg-blue-900 dark:text-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>      
                    </div>
                </div>

                
            </div>
            <!-- End Card -->
        </div>

        <div class="">
            <div class="">
                <div class="mb-5">
                    <flux:dropdown>
                        <flux:button type="button" icon:trailing="chevron-down">Filter</flux:button>
                        <flux:menu>
                            <flux:menu.submenu heading="Status">
                                <flux:select size="sm" wire:model.lazy="status"  placeholder="Pilih Status..." >
                                    @foreach (\App\Enums\StatusType::cases() as $status)
                                        <flux:select.option :value="$status->value">{{ $status->value }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:menu.submenu>

                            <flux:menu.submenu heading="Years">
                                <flux:select size="sm" wire:model.lazy="years"  placeholder="Pilih Tahun..." >
                                    @foreach ($getYears as $year)
                                        <flux:select.option :value="$year">{{ $year }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:menu.submenu>

                            <flux:menu.separator />

                            <flux:menu.item wire:click="resetFilter"  variant="danger" icon="x-mark">Reset</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 place-content-center">
                    <div class="p-4 md:p-5 min-h-102.5 bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-[#343A3F] dark:text-white">
                        <div class="" id="chart-month"></div>
                    </div>
                    <div class="p-4 md:p-5 min-h-102.5 bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-[#343A3F] dark:text-white" id="chart-pie-products"></div>
                    <div class="p-4 md:p-5 min-h-102.5 bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-[#343A3F] dark:text-white" id="chart-pie-piutang"></div>
                </div>
            </div>
        </div>
        
        <div class="my-5">
            <x-loading wire:loading wire:target="downloadExcel">
                Exporting Or Import In Progress Please Wait
            </x-loading>

            <div class="space-y-5">
                <x-flash-message />
            </div>
        </div>

        <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
            <div class="flex justify-between">
                <div class="flex flex-col">
                    <flux:heading>Customer Piutang</flux:heading>   
                    <flux:text class="mb-2">Customer Age Piutang.</flux:text>
                </div>
                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down">Options</flux:button>

                    <flux:menu>
                        <flux:menu.group heading="Export">
                            <flux:menu.item icon="document-arrow-down" wire:click="downloadExcel" wire:loading.remove wire:loading.attr="disabled" class="cursor-pointer">Excel</flux:menu.item>
                        </flux:menu.group>
                    </flux:menu>
                </flux:dropdown>
            </div>
            
            <div class="border border-gray-200 rounded-lg overflow-auto dark:border-neutral-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                Nama Customer</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                Total Piutang</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                Sisa Piutang</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                Umur 0-30 Hari</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                Umur 31-60 Hari</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                Umur 61-90 Hari</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                Umur > 90 Hari</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                        @forelse ($ageCustomer as $index =>$customer)
                        <tr>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $index + $ageCustomer->firstItem() }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $customer->user_name }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $customer->total_piutang }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $customer->sisa_piutang }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $customer->age_0_30 }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $customer->age_31_60 }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $customer->age_61_90 }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $customer->age_90_plus }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                <flux:button :href="route('company.customer-piutang', $customer->customer_uuid)">
                                    Detail
                                </flux:button>
                            </td>
                        </tr>
                        @empty
                        <tr class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            <td colspan="8" class="text-center text-gray-900 dark:text-white py-5">No Record
                                Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="my-4">
                {{ $ageCustomer->links() }}
            </div>
        </div>
    </div>
</div>

@script
<script>
    const chartData = {!! json_encode($totalPiutangByMonth) !!};
    const chartCategories = {!! json_encode($month) !!};
    const seriesProducts = {!! json_encode($seriesProducts) !!}
    const labelsProducts = {!! json_encode($labelsProducts) !!}
    const seriesPiutangCount = {!! json_encode($countPiutang) !!};
    const labelsPiutangLabels = {!! json_encode($statusPiutang) !!};

    const appearance = localStorage.getItem('flux.appearance') || 'system';
    const isDark = appearance === 'dark' || 
                (appearance === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

    console.log(seriesProducts)
    function getChartOptions(data, categories) {
        return {
            colors: ['#FFA500'],
            series: [{
                name: 'Jumlah Piutang',
                data: data
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            theme: {
                mode: isDark ? 'dark' : 'light'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Jumlah Piutang By Month',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: categories
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                        }).format(val);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                        }).format(val);
                    }
                }
            }
        };
    }
    const options = getChartOptions(chartData, chartCategories);
    const chartMonth = new ApexCharts(document.querySelector("#chart-month"), options);

    chartMonth.render();
    Livewire.on('filter', (data) => {
        setTimeout(() => {
            chartMonth.updateSeries([{
                name: 'Jumlah Piutang',
                data: data[0].orders,
            }]);

            chartMonth.updateOptions({
                colors: [`${data[0].colors}`],
            });
        }, 300); // delay 300ms
    });


    function getChartPieProducts(data, labels) {
        return {
            series: data,
            chart: {
                width: 500,
                type: 'pie',
            },
            title: {
                text: 'Distribusi Produk Piutang',
                align: 'center'
            },
            theme: {
                mode: isDark ? 'dark' : 'light'
            },
            labels: labels,
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                show: false
            },
            tooltip: {
                enabled: true // Matikan tooltip
            },
            responsive: [
                {
                    breakpoint: 1024,
                    options: {
                        chart: {
                            width: 400
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            show: false
                        }
                    }
                },
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            show: false
                        }
                    }
                },
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            show: false
                        }
                    }
                }
            ]
        }
    }

    const optionPie = getChartPieProducts(seriesProducts, labelsProducts);
    const chartPie = new ApexCharts(document.querySelector("#chart-pie-products"), optionPie);
    chartPie.render();


    function getChartPiePiutang(data, labels) {
        return {
            series: data,
            chart: {
                width: 500,
                type: 'pie',
            },
            title: {
                text: 'Piutang Status',
                align: 'center'
            },
            theme: {
                mode: isDark ? 'dark' : 'light'
            },
            labels: labels,
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                show: true
            },
            tooltip: {
                enabled: true // Matikan tooltip
            },
            responsive: [
                {
                    breakpoint: 1024,
                    options: {
                        chart: {
                            width: 400
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            show: false
                        }
                    }
                },
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            show: false
                        }
                    }
                },
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            show: false
                        }
                    }
                }
            ]
        }
    }

    const optionPiePiutang = getChartPiePiutang(seriesPiutangCount,labelsPiutangLabels);
    const chartPiePiutang = new ApexCharts(document.querySelector("#chart-pie-piutang"), optionPiePiutang);
    chartPiePiutang.render();
</script>
@endscript





