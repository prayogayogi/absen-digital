<div class="flex items-center space-x-4">
    <div>
        @if (!isset(Setting::getValueFile('app_logo')[0]))
            <div class="fi-logo flex text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white">
                {{ env('APP_NAME', 'Laravel') }}
            </div>
        @else
            <img src="{{ isset(Setting::getValueFile('app_logo')[0]) ? asset('storage/settings/' . Setting::getValueFile('app_logo')[0]) : '' }}" alt="Logo" class="h-10" width="170">
        @endif
    </div>
</div>
