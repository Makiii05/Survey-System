@if (session('success') || session('error') || session('info'))
<div id="toaster" class="fixed top-5 right-5 z-50 space-y-2">
    @if (session('success'))
    <div class="toast flex items-center gap-3 px-5 py-3 rounded-lg shadow-lg text-white border border-white/30" style="background-color: #004179;">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if (session('error'))
    <div class="toast flex items-center gap-3 px-5 py-3 rounded-lg shadow-lg text-white bg-red-600 border border-red-400">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif
    @if (session('info'))
    <div class="toast flex items-center gap-3 px-5 py-3 rounded-lg shadow-lg border" style="background-color: #f3c404; color: #004179; border-color: #d4a904;">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
        </svg>
        <span>{{ session('info') }}</span>
    </div>
    @endif
</div>
<script>
    setTimeout(() => {
        const toaster = document.getElementById('toaster');
        if (toaster) {
            toaster.style.transition = 'opacity 0.5s';
            toaster.style.opacity = '0';
            setTimeout(() => toaster.remove(), 500);
        }
    }, 4000);
</script>
@endif
