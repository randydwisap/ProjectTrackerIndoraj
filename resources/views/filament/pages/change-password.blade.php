<x-filament::page>
    <form wire:submit.prevent="changePassword" class="space-y-4">
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
            <input type="password" id="current_password" wire:model="current_password"
                class="mt-1 block w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                @error('current_password') 
                <span style="color: red !important; font-size: 14px;">{{ $message }}</span> 
            @enderror
            
        </div>

        <div>
            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" id="new_password" wire:model="new_password"
                class="mt-1 block w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                @error('new_password') 
                <span style="color: red !important; font-size: 14px;">{{ $message }}</span> 
            @enderror
            
        </div>

        <div>
            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation"
                class="mt-1 block w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                @error('new_password_confirmation') 
                <span style="color: red !important; font-size: 14px;">{{ $message }}</span> 
            @enderror
        </div>
        <button type="submit" style="
        background-color: red !important; 
        color: white !important; 
        padding: 5px 20px; 
        border-radius: 5px;
        width: 100px;  /* Lebar tombol */
        height: 35px;  /* Tinggi tombol */
        font-size: 14px; /* Ukuran teks */
    ">
        Simpan
    </button>
    

    </form>
</x-filament::page>
