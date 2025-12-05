@props(['title', 'backRoute' => null, 'backLabel' => 'Back to List'])

<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4 mb-6">
    <!-- Title Section -->
    <div class="flex items-center space-x-3">
        @if($backRoute)
            <!-- Back Button (Mobile & Desktop) -->
            <a href="{{ $backRoute }}" class="inline-flex items-center p-2 sm:px-3 sm:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline font-semibold">{{ $backLabel }}</span>
            </a>
        @endif
        
        <!-- Title -->
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </div>

    <!-- Action Slot (for buttons, etc) -->
    @if(isset($action))
        <div class="flex-shrink-0">
            {{ $action }}
        </div>
    @endif
</div>
