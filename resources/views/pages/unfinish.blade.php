<x-layouts.homePage title="Unfinish">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="flex justify-center items-center min-h-screen flex-col text-center">
        <img src="{{ asset('images/success.svg') }}" alt="">

        <h2 class="text-2xl text-red-800 font-bold my-5">Oops!</h2>
        <p class="lg:text-xl text-lg font-light text-slate-500">
            Pembayaran Anda belum selesai.
            <br />
            Silakan lanjutkan pembayaran untuk menyelesaikan transaksi
        </p>
    </div>
</x-layouts.homePage>
