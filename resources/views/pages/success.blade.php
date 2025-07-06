<x-layouts.export.pdf title="Success">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="flex justify-center items-center min-h-screen flex-col text-center">
        <img src="{{ asset('images/success.svg') }}" alt="">

        <h2 class="text-2xl text-neutral-800 font-bold my-5">Transaction Processed!</h2>
        <p class="lg:text-xl text-lg font-light text-slate-500">
            Silakan tunggu konfirmasi email dari kami.
            <br />
            Pembayaran Anda telah berhasil diproses!
        </p>
    </div>
</x-layouts.export.pdf>
