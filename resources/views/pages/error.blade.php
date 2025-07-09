<x-layouts.homePage title="Error">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="flex justify-center items-center min-h-screen flex-col text-center">
        <img src="{{ asset('images/error.svg') }}" alt="">

        <h2 class="text-2xl text-red-800 font-bold my-5">Oops!</h2>
        <p class="lg:text-xl text-lg font-light text-slate-500">
            Terjadi kesalahan dalam memproses pembayaran.
            <br />
            Silakan coba lagi nanti atau hubungi dukungan pelanggan.
        </p>
    </div>
</x-layouts.homePage>
