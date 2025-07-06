<div {{ $attributes->merge([
    'class' => 'bg-gray-50 border border-gray-200 text-sm text-gray-600 rounded-lg p-4 dark:bg-white/10 dark:border-white/10 dark:text-neutral-400',
    'role' => 'alert',
    'tabindex' => '-1',
    'aria-labelledby' => 'hs-link-on-right-label'
]) }}>
    <div class="flex">
        <div class="shrink-0">
            <svg class="shrink-0 size-4 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 16v-4"></path>
                <path d="M12 8h.01"></path>
            </svg>
        </div>
        <div class="flex-1 md:flex md:justify-between ms-2">
            <p id="hs-link-on-right-label" class="text-sm">
                {{ $slot ?? 'Exporting Users In Progress Please Wait' }}
            </p>
        </div>
    </div>
</div>
