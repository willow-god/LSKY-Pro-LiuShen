<!-- Strategies dropdown -->
<x-dropdown>
    <x-slot name="trigger">
        <button type="button" class="flex items-center gap-1.5 pl-1 pr-2 py-1 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-200" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
            <div class="h-8 w-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(99,102,241,0.1);">
                <i class="fas fa-server text-emerald-500 text-xs"></i>
            </div>
            <span class="text-sm font-medium hidden sm:block" id="strategy-selected" data-id="0">获取中...</span>
            <i class="fas fa-chevron-down text-xs text-slate-400 hidden sm:block"></i>
        </button>
    </x-slot>

        <x-slot name="content">
            <div id="strategies">
            @foreach($_group->strategies as $strategy)
                <x-dropdown-link data-id="{{ $strategy->id }}" href="javascript:void(0)" @click="open = false">{{ $strategy->name }}</x-dropdown-link>
            @endforeach
            </div>
        </x-slot>
</x-dropdown>

@push('scripts')
    <script>
        let defaultStrategy = {{ Auth::check() ? Auth::user()->configs->get('default_strategy') : 0 }} || (localStorage.getItem('strategy') || 0);
        let setStrategy = function (id) {
            let isSelected = false;
            $('#strategies a').each(function () {
                if (parseInt($(this).data('id')) === parseInt(id)) {
                    localStorage.setItem('strategy', id)
                    $('#strategy-selected').text($(this).text()).data('id', id);
                    isSelected = true;

                    @if(Auth::check())
                        if (defaultStrategy != id) {
                            axios.put('{{ route('settings.strategy.set') }}', {id: id}).then(response => {
                                if (! response.data.status) {
                                    toastr.error(response.data.message);
                                }
                            });
                        }
                    @endif
                }
            });
            if (! isSelected) {
                let $first = $('#strategies a:first-child');
                localStorage.setItem('strategy', $first.data('id'))
                $('#strategy-selected').text($first.text()).data('id', $first.data('id'));
            }
        };

        setStrategy(defaultStrategy);

        $('#strategies a').click(function () {
            setStrategy($(this).data('id'))
        });
    </script>
@endpush
