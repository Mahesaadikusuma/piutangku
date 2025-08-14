<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Card -->
            <div
                class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                <div class="p-4 md:p-5 flex justify-between gap-x-3">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                            Riwayat Transaksi
                        </p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="mt-1 text-xl font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data['totalTransactionsCount'] }}
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
                            Jumlah Piutang yang Dimiliki
                        </p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data['totalPiutangCount'] }}
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

            <div
                class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                <div class="p-4 md:p-5 flex justify-between gap-x-3">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                            Total Tagihan Piutang Keseluruhan
                        </p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data['totalJumlahPiutang'] }}
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
                        <div class="" id="chart-area-piutang"></div>
                    </div>
                    <div class="p-4 md:p-5 min-h-102.5 bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-[#343A3F] dark:text-white" id="chart-pie-piutang"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const totalPiutangByMonth = {!! json_encode($totalPiutangByMonth) !!};
    const sisaPiutangByMonth = {!! json_encode($sisaPiutangByMonth) !!};
    const chartCategories = {!! json_encode($month) !!};
    const seriesPiutangCount = {!! json_encode($countPiutang) !!};
    const labelsPiutangLabels = {!! json_encode($statusPiutang) !!};

    const appearance = localStorage.getItem('flux.appearance') || 'system';
    const isDark = appearance === 'dark' || 
                (appearance === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
    function getChartAreaPiutang(series1,series2,categories){
        return {
            series: [{
                name: 'Total Piutang Terbayar',
                data: series1
            }, {
                name: 'Sisa Piutang',
                data: series2
            }],
            chart: {
                height: 350,
                type: 'area'
            },
            title: {
                text: 'Piutangs',
                align: 'left'
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
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
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
        }
    }

    const optionAreaPiutang = getChartAreaPiutang(totalPiutangByMonth,sisaPiutangByMonth,chartCategories);
    const chartAreaPiutang = new ApexCharts(document.querySelector("#chart-area-piutang"), optionAreaPiutang);
    chartAreaPiutang.render();
    Livewire.on('filter', (data) => {
        setTimeout(() => {
            console.log(data)
            chartAreaPiutang.updateSeries([{
                data: data[0].totalPiutang,
            },
            {
                data: data[0].sisaPiutang,
            }]);
        }, 300); // delay 300ms
    });


    function getChartPiePiutang(data, labels) {
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