<div class="relative pt-1">
    <div class="flex mb-2 items-center justify-between">
        <span class="text-sm font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600">
            {{ number_format($progress, 2) }}% <!-- Menampilkan nilai progress dengan format angka 2 desimal -->
        </span>
    </div>
    <div class="flex mb-2 items-center justify-between">
        <div class="w-full bg-gray-200 rounded-full">
            <div class="bg-teal-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-l-full"
                style="width: {{ $progress }}%"></div> <!-- Menampilkan progress bar -->
        </div>
    </div>
</div>
