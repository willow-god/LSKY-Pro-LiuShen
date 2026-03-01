@props(['classes' => ['left' => 'origin-top-right right-0', 'right' => 'origin-top-left left-0'], 'direction' => 'left'])

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false" x-cloak>
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 scale-95 translate-y-1"
         class="absolute z-[500] {{ $classes[$direction] }} mt-2 w-52 rounded-xl py-1.5 overflow-hidden"
         style="background: rgba(255,255,255,0.97); backdrop-filter: blur(12px); border: 1px solid rgba(226,232,240,0.8); box-shadow: 0 10px 40px rgba(0,0,0,0.1), 0 2px 8px rgba(0,0,0,0.06); display: none;"
         role="menu"
         aria-orientation="vertical"
         aria-labelledby="user-menu-button"
         tabindex="-1"
    >
        {{ $content }}
    </div>
</div>
