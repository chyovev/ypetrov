<x-admin.layout title="Poems" route="admin.poems.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-buttons">
                        <a class="btn btn-success" href="{{ route('admin.poems.create') }}"><i class="fa fa-plus"></i> Create</a>
                    </div>

                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th width="50" class="text-center">Public</th>
                                    <th>Title</th>
                                    <th>Text</th>
                                    <th class="text-center">Books</th>
                                    <th>Created at</th>
                                    <th width="150" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($poems as $poem)
                                <tr>
                                    <td class="text-center">{{ (($poems->currentPage() - 1) * $poems->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span @class(['fa' => true, 'fa-check text-success' => $poem->is_active, 'fa-times text-danger' => !$poem->is_active])></span>
                                    </td>
                                    <td>{{ $poem->title }}</td>
                                    <td>{{ Str::of($poem->text)->stripTags()->limit(60) }}</td>
                                    <td class="text-center">{{ $poem->books_count }}</td>
                                    <td>{{ $poem->created_at->format('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.poems.edit',    ['poem' => $poem]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> Edit</a>
                                        <a href="{{ route('admin.poems.destroy', ['poem' => $poem]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  Delete</a>
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