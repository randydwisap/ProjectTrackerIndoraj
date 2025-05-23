<x-filament::page>
    <div class="flex justify-center mt-12">
        <div class="w-full max-w-md space-y-4">
            <div class="flex justify-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-16">
            </div>
            {{ $this->form }}
            <p class="text-center text-sm text-gray-500 mt-4">
                &copy; {{ date('Y') }} asdasdasd Nama Perusahaan.
            </p>
        </div>
    </div>
</x-filament::page>
