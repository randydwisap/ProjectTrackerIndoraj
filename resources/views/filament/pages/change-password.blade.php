<x-filament::page>
    <style>
        .password-form-container {
            padding: 24px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }
        
        .dark .password-form-container {
            background-color: #1f2937;
        }
        
        .password-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        
        .dark .form-label {
            color: #d1d5db;
        }
        
        .required-marker {
            color: #ef4444;
        }
        
        .form-input {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            color: #111827;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }
        
        .dark .form-input {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
        
        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            width: fit-content;
        }
        
        .submit-btn:hover {
            background-color: #ef4444;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .dark .submit-btn {
            background-color: red;
        }
        
        .dark .submit-btn:hover {
            background-color: #ef4444;
        }
    </style>

    <div class="password-form-container">
        <form wire:submit.prevent="changePassword" class="password-form">
            <div class="form-group">
                <label for="current_password" class="form-label">
                    Current Password <span class="required-marker">*</span>
                </label>
                <input
                    type="password"
                    id="current_password"
                    wire:model.defer="current_password"
                    class="form-input"
                    required
                >
                @error('current_password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="new_password" class="form-label">
                    New Password <span class="required-marker">*</span>
                </label>
                <input
                    type="password"
                    id="new_password"
                    wire:model.defer="new_password"
                    class="form-input"
                    required
                >
                @error('new_password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="new_password_confirmation" class="form-label">
                    Confirm New Password <span class="required-marker">*</span>
                </label>
                <input
                    type="password"
                    id="new_password_confirmation"
                    wire:model.defer="new_password_confirmation"
                    class="form-input"
                    required
                >
                @error('new_password_confirmation')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="submit-btn">
                Change Password
            </button>
        </form>
    </div>
</x-filament::page>