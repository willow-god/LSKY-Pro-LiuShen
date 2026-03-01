@section('title', '角色组管理')

<x-app-layout>
    <div class="my-6 md:my-9">
        <div class="mb-5 flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(13,148,136,0.15));">
                <i class="fas fa-layer-group text-sm" style="color: #059669;"></i>
            </div>
            <h2 class="font-bold text-lg text-slate-800">角色组管理</h2>
        </div>
        <form action="{{ route('admin.groups') }}" method="get">
            <div class="mb-3 flex justify-between w-full">
                <x-button type="button" onclick="window.location.href = '{{ route('admin.group.create') }}'">创建角色组</x-button>
                <input class="px-3 py-1.5 text-sm rounded-lg bg-white border border-slate-200 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400" name="keywords" placeholder="输入名称回车搜索..." value="{{ request('keywords') }}" />
            </div>
        </form>

        <x-table :columns="['ID', '名称', '是否默认', '是否为游客组', '图片审核', '原图保护', '水印' ,'用户数量', '策略数量', '操作']">
            @foreach($groups as $group)
            <tr data-id="{{ $group->id }}">
                <td class="px-6 py-4 whitespace-nowrap">{{ $group->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap name">{{ $group->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->is_default ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->is_default ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->is_guest ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->is_guest ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->configs->get('is_enable_scan') ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->configs->get('is_enable_scan') ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->configs->get('is_enable_original_protection') ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->configs->get('is_enable_original_protection') ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->configs->get('is_enable_watermark') ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->configs->get('is_enable_watermark') ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $group->users_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $group->strategies_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('admin.group.edit', ['id' => $group->id]) }}" class="text-emerald-600 hover:text-emerald-900">编辑</a>
                    @if(! $group->is_default && ! $group->is_guest)
                    <a href="javascript:void(0)" data-operate="delete" class="text-red-600 hover:text-red-900">删除</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </x-table>
        @if($groups->isEmpty())
            <x-no-data message="没有找到任何角色组"/>
        @else
            <div class="mt-4">
                {{ $groups->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            $('[data-operate="delete"]').click(function () {
                Swal.fire({
                    title: `确认删除角色组【${$(this).closest('tr').find('td.name').text()}】吗?`,
                    text: "⚠️注意，删除该角色组后，该角色组下属的用户会被重置为系统默认组。",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '确认删除',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).closest('tr').data('id');
                        axios.delete(`/admin/groups/${id}`).then(response => {
                            if (response.data.status) {
                                history.go(0);
                            } else {
                                toastr.error(response.data.message);
                            }
                        });
                    }
                })
            });
        </script>
    @endpush

</x-app-layout>
