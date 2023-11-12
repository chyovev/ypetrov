<x-admin.layout title="Books" route="admin.books.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> Books
                        <div class="dt-buttons float-right">
                            <a class="btn btn-success" href="{{ route('admin.books.create') }}"><i class="fa fa-plus"></i> Create</a>
                        </div>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th width="50" class="text-center">Public</th>
                                    <th class="text-center" width="90">Remarks</th>
                                    <th>Title</th>
                                    <th>Publisher</th>
                                    <th class="text-center">Publish year</th>
                                    <th class="text-center">Poems</th>
                                    <th>Created at</th>
                                    <th width="150" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($books as $book)
                                <tr>
                                    <td class="text-center">{{ (($books->currentPage() - 1) * $books->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span @class(['fa' => true, 'fa-check text-success' => $book->is_active, 'fa-times text-danger' => !$book->is_active])></span>
                                    </td>
                                    <td class="text-center">
                                        <x-admin.remarks :object="$book" />
                                    </td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->publisher }}</td>
                                    <td class="text-center">{{ $book->publish_year }}</td>
                                    <td class="text-center">{{ $book->poems_count }}</td>
                                    <td>{{ $book->created_at->format('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.books.edit',    ['book' => $book]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> Edit</a>
                                        <a href="{{ route('admin.books.destroy', ['book' => $book]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="m-t-30">{{ $books->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>