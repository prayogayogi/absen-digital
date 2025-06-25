<div class="flex items-center gap-x-3 me-3">
    <div
        x-data="{
            mode: localStorage.getItem('theme') || 'system',
            setTheme(theme) {
                this.mode = theme;
                localStorage.setItem('theme', theme);
                if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }"
        x-init="setTheme(mode)"
        class="flex items-center gap-1 bg-gray-700 dark:bg-gray-800 rounded-full p-1 w-[72px] justify-between"
    >
        <!-- Light Icon -->
        <button
            x-on:click="setTheme('light')"
            :class="mode === 'light' ? ' text-yellow-500' : 'text-gray-400'"
            class="rounded-full p-1 transition-colors"
            title="Light mode"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 4.5a1 1 0 011-1V2a1 1 0 10-2 0v1.5a1 1 0 011 1zM18.364 5.636l1.061-1.06a1 1 0 10-1.415-1.415l-1.06 1.06a1 1 0 101.414 1.415zM21 11h-1.5a1 1 0 100 2H21a1 1 0 100-2zM18.364 18.364a1 1 0 10-1.414-1.414l-1.061 1.06a1 1 0 001.415 1.415l1.06-1.061zM12 19.5a1 1 0 00-1 1V22a1 1 0 002 0v-1.5a1 1 0 00-1-1zM5.636 18.364l-1.06 1.061a1 1 0 001.415 1.415l1.06-1.06a1 1 0 10-1.415-1.415zM3 13h1.5a1 1 0 100-2H3a1 1 0 100 2zM5.636 5.636a1 1 0 001.414-1.415l-1.06-1.06a1 1 0 00-1.415 1.415l1.061 1.06zM12 6.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11z">
                </path>
            </svg>
        </button>

        <!-- Dark Icon -->
        <button
            x-on:click="setTheme('dark')"
            :class="mode === 'dark' ? ' text-yellow-500' : 'text-gray-400'"
            class="rounded-full p-1 transition-colors"
            title="Dark mode"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M21 12.79A9 9 0 0112.21 3 7 7 0 1012 21a9 9 0 009-8.21z">
                </path>
            </svg>
        </button>
    </div>


    <a href="/" target="_blank" class="
        fi-btn fi-btn-size-md fi-btn-outline fi-btn-color-gray
        relative grid-flow-col items-center justify-center font-semibold outline-none
        transition duration-75 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-70
        text-sm
    ">
        <span class="fi-btn-label"><x-heroicon-o-globe-alt class="h-5 w-5" /></span>
    </a>


</div>
