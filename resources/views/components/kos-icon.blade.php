@props(['name'])
<svg {{ $attributes->merge(['width' => 20, 'height' => 20]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('home') <path d="m3 10 9-7 9 7v10H3zM9 20v-7h6v7"/> @break
        @case('arrow') <path d="M4 12h15m-6-6 6 6-6 6"/> @break
        @case('external') <path d="M6 18 18 6M7 6h11v11"/> @break
        @case('bed') <path d="M3 18v-8m18 8v-5a2 2 0 0 0-2-2h-8v5m-8 0h18M6 11V8h4v3m-7 0h8"/> @break
        @case('chat') <path d="M20 11a8 8 0 0 1-8 8 9 9 0 0 1-3-.5L4 20l1.5-4.5A8 8 0 1 1 20 11Z"/><path d="M8.5 11h.01m3.5 0h.01m3.5 0h.01" stroke-width="2.5"/> @break
        @case('search') <circle cx="10.5" cy="10.5" r="5.5"/><path d="m15 15 5 5"/> @break
        @case('grid') <rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/> @break
        @case('wallet') <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18m-14 5h3"/> @break
        @case('drop') <path d="M12 3c-2 4-6 8-6 12a6 6 0 0 0 12 0c0-4-4-8-6-12Z"/> @break
        @case('tool') <path d="M15 3a5 5 0 0 0-5 6l-6 6a2.5 2.5 0 0 0 4 4l6-6a5 5 0 0 0 6-6l-3 3-3-3 3-4Z"/> @break
        @case('photo') <rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8" cy="9" r="1.5"/><path d="m4 18 6-5 3 3 4-5 4 4"/> @break
        @case('users') <circle cx="9" cy="8" r="3"/><path d="M3 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2m1-15a3 3 0 0 1 0 6m1 3a4 4 0 0 1 4 4v2"/> @break
        @case('menu') <path d="M4 6h16M4 12h16M4 18h16"/> @break
        @case('out') <path d="M10 4H4v16h6m-1-8h12m-4-4 4 4-4 4"/> @break
        @case('close') <path d="m5 5 14 14M19 5 5 19"/> @break
    @endswitch
</svg>
