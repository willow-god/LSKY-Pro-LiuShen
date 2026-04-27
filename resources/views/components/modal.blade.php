@props(['id' => 'modal'])

<div {{ $attributes->merge(['id' => $id, 'class' => "fixed z-50 inset-0 overflow-y-auto"]) }} role="dialog" aria-modal="true" x-data x-cloak x-show="$store.modal.isOpen('{{ $id }}')">
    <div class="flex min-h-screen text-center md:block md:px-2 lg:px-4" style="font-size: 0">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-show="$store.modal.isOpen('{{ $id }}')"
             @click="$store.modal.close('{{ $id }}')"
             class="hidden fixed inset-0 transition-opacity md:block"
             style="background: rgba(15,23,42,0.55); backdrop-filter: blur(4px);"
             aria-hidden="true"
        >
        </div>
        <span class="hidden md:inline-block md:align-middle md:h-screen" aria-hidden="true">&#8203;</span>

        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
             x-show="$store.modal.isOpen('{{ $id }}')"
             class="flex text-base text-left transform transition w-full md:inline-block md:max-w-2xl md:px-4 md:my-8 md:align-middle lg:max-w-4xl"
        >
            <div class="w-full relative flex px-4 pt-12 pb-6 overflow-hidden sm:px-6 sm:pt-10 md:p-6 lg:p-8 rounded-2xl" style="background: var(--modal-bg); box-shadow: 0 25px 60px rgba(0,0,0,0.15), 0 8px 20px rgba(0,0,0,0.08); border: 1px solid var(--modal-border);">
                <button type="button" class="absolute top-3 right-3 w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-150" @click="$store.modal.close('{{ $id }}')">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times text-sm"></i>
                </button>

                <div class="flex items-center justify-center h-24 w-full" x-show="$store.modal.isLoading('{{ $id }}')">
                    <x-loading-spin />
                </div>

                <div class="w-full text-[var(--text-primary)]" x-show="! $store.modal.isLoading('{{ $id }}')">
                    {{ $slot ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>
