<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <button onclick="window.history.back()" class="inline-flex items-center p-2 sm:px-3 sm:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline font-semibold">Back</span>
            </button>
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Edit Promo') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 md:p-8">
                    {{-- Header --}}
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Edit Promo Code</h3>
                                <p class="text-sm text-gray-600 mt-1">Update the promotional discount code details</p>
                            </div>
                        </div>
                        
                        {{-- Status Badge --}}
                        <div class="mt-4">
                            @if($promo->valid_until->isFuture())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Active Promo
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Expired Promo
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-red-800 mb-1">Please fix the following errors:</h4>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('admin.promos.update', $promo) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Promo Code --}}
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                                Promo Code <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="code" 
                                       id="code" 
                                       value="{{ old('code', $promo->code) }}"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('code') border-red-500 @enderror"
                                       placeholder="e.g., SEBLAK50, DISKON25"
                                       required
                                       maxlength="255">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Use uppercase letters and numbers. Must be unique.
                            </p>
                            @error('code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Discount Percentage --}}
                        <div>
                            <label for="discount" class="block text-sm font-semibold text-gray-700 mb-2">
                                Discount Percentage <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input type="number" 
                                       name="discount" 
                                       id="discount" 
                                       value="{{ old('discount', $promo->discount) }}"
                                       class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('discount') border-red-500 @enderror"
                                       placeholder="25"
                                       min="0"
                                       max="100"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm font-medium">%</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Enter a value between 0 and 100
                            </p>
                            @error('discount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Valid Until --}}
                        <div>
                            <label for="valid_until" class="block text-sm font-semibold text-gray-700 mb-2">
                                Valid Until <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="date" 
                                       name="valid_until" 
                                       id="valid_until" 
                                       value="{{ old('valid_until', $promo->valid_until->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('valid_until') border-red-500 @enderror"
                                       required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                You can extend or keep the current expiry date
                            </p>
                            @error('valid_until')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Preview Card --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-dashed border-blue-300 rounded-xl p-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                            </h4>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Promo Code</p>
                                        <p class="text-lg font-bold text-gray-900" id="preview-code">{{ $promo->code }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Discount</p>
                                        <p class="text-2xl font-bold text-blue-600" id="preview-discount">{{ $promo->discount }}%</p>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-500">Valid until: <span class="font-semibold text-gray-700" id="preview-date">{{ $promo->valid_until->format('F d, Y') }}</span></p>
                                </div>
                            </div>
                        </div>

                        {{-- Info Box --}}
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <h5 class="text-sm font-semibold text-yellow-800 mb-1">Important Note</h5>
                                    <p class="text-sm text-yellow-700">Changing the promo code will affect all existing references. Make sure no active orders are using this code before making changes.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Promo
                            </button>
                            <button type="button" onclick="window.history.back()" 
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                            <button type="button" 
                                    onclick="if(confirm('Are you sure you want to delete this promo? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }"
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-xl transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Promo
                            </button>
                        </div>
                    </form>

                    {{-- Delete Form (Hidden) --}}
                    <form id="delete-form" method="POST" action="{{ route('admin.promos.destroy', $promo) }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            {{-- Metadata Section --}}
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">Promo Information</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Created At</p>
                        <p class="font-semibold text-gray-900">{{ $promo->created_at->format('F d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Last Updated</p>
                        <p class="font-semibold text-gray-900">{{ $promo->updated_at->format('F d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Days Until Expiry</p>
                        <p class="font-semibold text-gray-900">
                            @if($promo->valid_until->isFuture())
                                {{ $promo->valid_until->diffInDays(now()) }} days
                            @else
                                Expired {{ $promo->valid_until->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Promo ID</p>
                        <p class="font-semibold text-gray-900">#{{ $promo->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Live Preview Script --}}
    <script>
        // Live preview functionality
        const codeInput = document.getElementById('code');
        const discountInput = document.getElementById('discount');
        const dateInput = document.getElementById('valid_until');
        
        const previewCode = document.getElementById('preview-code');
        const previewDiscount = document.getElementById('preview-discount');
        const previewDate = document.getElementById('preview-date');

        function updatePreview() {
            previewCode.textContent = codeInput.value || '-';
            previewDiscount.textContent = (discountInput.value || '0') + '%';
            
            if (dateInput.value) {
                const date = new Date(dateInput.value);
                previewDate.textContent = date.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            } else {
                previewDate.textContent = '-';
            }
        }

        codeInput.addEventListener('input', updatePreview);
        discountInput.addEventListener('input', updatePreview);
        dateInput.addEventListener('change', updatePreview);

        // Auto-uppercase promo code
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Validate discount range
        discountInput.addEventListener('input', function() {
            if (this.value > 100) this.value = 100;
            if (this.value < 0) this.value = 0;
        });
    </script>
</x-admin-layout>
