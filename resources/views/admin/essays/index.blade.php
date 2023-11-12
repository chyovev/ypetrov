<x-admin.layout route="admin.essays.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> {{ __('global.essays') }}
                        <div class="dt-buttons float-right">
                            <a class="btn btn-success" href="{{ route('admin.essays.create') }}"><i class="fa fa-plus"></i> {{ __('global.create') }}</a>
                        </div>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th width="50" class="text-center">{{ __('global.public') }}</th>
                                    <th class="text-center" width="90">{{ __('global.remarks') }}</th>
                                    <th>{{ __('global.title') }}</th>
                                    <th>{{ __('global.text') }}</th>
                                    <th>{{ __('global.created_at') }}</th>
                                    <th width="200" class="text-center">{{ __('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($essays as $essay)
                                <tr>
                                    <td class="text-center">{{ (($essays->currentPage() - 1) * $essays->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span @class(['fa' => true, 'fa-check text-success' => $essay->is_active, 'fa-times text-danger' => !$essay->is_active])></span>
                                    </td>
                                    <td class="text-center">
                                        <x-admin.remarks :object="$essay" />
                                    </td>
                                    <td>{{ $essay->title }}</td>
                                    <td>{{ Str::of($essay->text)->stripTags()->limit(60) }}</td>
                                    <td>{{ $essay->created_at->format('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.essays.edit',    ['essay' => $essay]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> {{ __('global.edit') }}</a>
                                        <a href="{{ route('admin.essays.destroy', ['essay' => $essay]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  {{ __('global.delete') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="m-t-30">{{ $essays->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>