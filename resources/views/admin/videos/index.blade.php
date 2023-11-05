<x-admin.layout title="Videos" route="admin.videos.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-buttons">
                        <a class="btn btn-success" href="{{ route('admin.videos.create') }}"><i class="fa fa-plus"></i> Create</a>
                    </div>

                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th width="50" class="text-center">Public</th>
                                    <th>Title</th>
                                    <th>Summary</th>
                                    <th>Publish date</th>
                                    <th>Created at</th>
                                    <th width="150" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($videos as $video)
                                <tr>
                                    <td class="text-center">{{ (($videos->currentPage() - 1) * $videos->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span @class(['fa' => true, 'fa-check text-success' => $video->is_active, 'fa-times text-danger' => !$video->is_active])></span>
                                    </td>
                                    <td>{{ $video->title }}</td>
                                    <td>{{ Str::of($video->summary)->stripTags()->limit(60) }}</td>
                                    <td>
                                        @if ($video->publish_date)
                                            {{ $video->publish_date->format('d.m.Y.') }}
                                        @endif
                                    </td>
                                    <td>{{ $video->created_at->format('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.videos.edit',    ['video' => $video]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> Edit</a>
                                        <a href="{{ route('admin.videos.destroy', ['video' => $video]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="m-t-30">{{ $videos->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>