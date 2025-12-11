@auth
    <x-app-layout>
        <!-- Custom Header Orange -->
        <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-8 pt-6 shadow-lg rounded-b-[2rem] mb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 text-white">
                <a href="{{ route('dashboard') }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition-colors backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-2xl font-bold">Ulasan Pelanggan</h1>
            </div>
        </div>
        
        <div class="py-12">
            @include('testimonials.partials.content')
        </div>
    </x-app-layout>
@else
    <x-guest-layout>
        <x-navbar :transparent="false" />
        
        <div class="bg-slate-50 py-24">
            @include('testimonials.partials.content')
        </div>

        <x-footer />
    </x-guest-layout>
@endauth

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle star rating selection
        const ratingLabels = document.querySelectorAll('.rating-label');
        
        if (ratingLabels.length > 0) {
            ratingLabels.forEach((label, index) => {
                const input = label.querySelector('input');
                const star = label.querySelector('svg');
                
                // Handle hover
                label.addEventListener('mouseenter', () => {
                    // Fill in this star and all previous stars
                    for (let i = 0; i <= index; i++) {
                        ratingLabels[i].querySelector('svg').classList.add('text-yellow-400');
                        ratingLabels[i].querySelector('svg').classList.remove('text-gray-300');
                    }
                    // Clear all next stars
                    for (let i = index + 1; i < ratingLabels.length; i++) {
                        ratingLabels[i].querySelector('svg').classList.remove('text-yellow-400');
                        ratingLabels[i].querySelector('svg').classList.add('text-gray-300');
                    }
                });

                // Handle click
                input.addEventListener('change', () => {
                    // Clear all stars
                    ratingLabels.forEach(label => {
                        label.querySelector('svg').classList.remove('text-yellow-400');
                        label.querySelector('svg').classList.add('text-gray-300');
                    });
                    // Fill in selected stars
                    for (let i = 0; i <= index; i++) {
                        ratingLabels[i].querySelector('svg').classList.add('text-yellow-400');
                        ratingLabels[i].querySelector('svg').classList.remove('text-gray-300');
                    }
                });
            });

            // Reset stars when mouse leaves the container
            const ratingContainer = document.querySelector('.rating-label').parentElement;
            if (ratingContainer) {
                ratingContainer.addEventListener('mouseleave', () => {
                    const selectedRating = document.querySelector('input[name="rating"]:checked');
                    if (selectedRating) {
                        const selectedIndex = parseInt(selectedRating.value) - 1;
                        ratingLabels.forEach((label, index) => {
                            const star = label.querySelector('svg');
                            if (index <= selectedIndex) {
                                star.classList.add('text-yellow-400');
                                star.classList.remove('text-gray-300');
                            } else {
                                star.classList.remove('text-yellow-400');
                                star.classList.add('text-gray-300');
                            }
                        });
                    } else {
                        ratingLabels.forEach(label => {
                            label.querySelector('svg').classList.remove('text-yellow-400');
                            label.querySelector('svg').classList.add('text-gray-300');
                        });
                    }
                });
            }
        }
    });
</script>
@endpush
