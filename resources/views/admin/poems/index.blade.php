<x-admin.layout route="admin.poems.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> {{ __('global.poems') }}
                        <x-admin.total-results :items="$poems" />
                        <div class="dt-buttons float-right ml-2">
                            <a class="btn btn-success" href="{{ route('admin.poems.create') }}"><i class="fa fa-plus"></i> {{ __('global.create') }}</a>
                        </div>
                        <x-admin.search />
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
                                    <th class="text-center">{{ __('global.books') }}</th>
                                    <th width="240">{{ __('global.created_at') }}</th>
                                    <th width="200" class="text-center">{{ __('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($poems as $poem)
                                <tr>
                                    <td class="text-center">{{ (($poems->currentPage() - 1) * $poems->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span @class(['fa' => true, 'fa-check text-success' => $poem->is_active, 'fa-times text-danger' => !$poem->is_active])></span>
                                    </td>
                                    <td class="text-center">
                                        <x-admin.remarks :object="$poem" />
                                    </td>
                                    <td>{{ $poem->title }}</td>
                                    <td>{{ Str::of($poem->text)->stripTags()->limit(50) }}</td>
                                    <td class="text-center">{{ $poem->books_count }}</td>
                                    <td>{{ $poem->created_at->translatedFormat('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.poems.edit',    ['poem' => $poem]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> {{ __('global.edit') }}</a>
                                        <a href="{{ route('admin.poems.destroy', ['poem' => $poem]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  {{ __('global.delete') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="m-t-30">{{ $poems->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>