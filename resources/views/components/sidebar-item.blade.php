@props(['route', 'icon', 'label'])

@php
    $active = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
          {{ $active
              ? 'bg-indigo-600 text-white font-medium'
              : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
    <span>{{ $icon }}</span>
    <span>{{ $label }}</span>
</a>