{{-- Stats Widget Component --}}
@props(['title', 'value', 'icon', 'color' => 'primary', 'trend' => null, 'url' => null])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-all hover:shadow-md {{ $url ? 'cursor-pointer' : '' }}" 
     @if($url) onclick="window.location.href='{{ $url }}'" @endif>
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" id="stat-{{ Str::slug($title) }}">
                {{ $value }}
            </p>
            @if($trend)
                <div class="flex items-center mt-2">
                    <span class="material-icons-round text-sm {{ $trend > 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ $trend > 0 ? 'trending_up' : 'trending_down' }}
                    </span>
                    <span class="text-xs {{ $trend > 0 ? 'text-green-600' : 'text-red-600' }} ml-1">
                        {{ abs($trend) }}%
                    </span>
                </div>
            @endif
        </div>
        <div class="w-12 h-12 rounded-full flex items-center justify-center
            @switch($color)
                @case('primary') bg-primary/10 text-primary @break
                @case('green') bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 @break
                @case('blue') bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 @break
                @case('yellow') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 @break
                @case('red') bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 @break
                @default bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400
            @endswitch
        ">
            <span class="material-icons-round text-xl">{{ $icon }}</span>
        </div>
    </div>
</div>