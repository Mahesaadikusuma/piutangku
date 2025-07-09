<x-layouts.homePage title="Failed">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="flex justify-center items-center min-h-screen flex-col text-center">
        <img src="{{ asset('images/error-link.svg') }}" alt="">

        <h2 class="text-2xl text-red-800 font-bold my-5">Oops!</h2>
        <p class="lg:text-xl text-lg font-light text-slate-500">
            Pembayaran Anda gagal. .
            <br />
            Silakan coba lagi atau gunakan metode pembayaran lain
        </p>
    </div>
</x-layouts.homePage>
