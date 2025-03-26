<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    @foreach ($imageUrls as $imageUrl)
        <div class="border p-2 rounded-lg">
            <img src="{{ $imageUrl }}" class="w-full h-auto rounded-lg shadow-lg">
        </div>
    @endforeach
</div>
