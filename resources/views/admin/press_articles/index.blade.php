<x-admin.layout title="Press Articles" route="admin.press_articles.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> Press Articles
                        <div class="dt-buttons float-right">
                            <a class="btn btn-success" href="{{ route('admin.press_articles.create') }}"><i class="fa fa-plus"></i> Create</a>
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
                                    <th>Text</th>
                                    <th>Created at</th>
                                    <th width="150" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pressArticles as $pressArticle)
                                <tr>
                                    <td class="text-center">{{ (($pressArticles->currentPage() - 1) * $pressArticles->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span @class(['fa' => true, 'fa-check text-success' => $pressArticle->is_active, 'fa-times text-danger' => !$pressArticle->is_active])></span>
                                    </td>
                                    <td class="text-center">
                                        <x-admin.remarks :object="$pressArticle" />
                                    </td>
                                    <td>{{ $pressArticle->title }}</td>
                                    <td>{{ Str::of($pressArticle->text)->stripTags()->limit(60) }}</td>
                                    <td>{{ $pressArticle->created_at->format('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.press_articles.edit',    ['press_article' => $pressArticle]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> Edit</a>
                                        <a href="{{ route('admin.press_articles.destroy', ['press_article' => $pressArticle]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="m-t-30">{{ $pressArticles->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>