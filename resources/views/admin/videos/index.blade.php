<x-admin.layout route="admin.videos.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> {{ __('global.videos') }}
                        <div class="dt-buttons float-right">
                            <a class="btn btn-success" href="{{ route('admin.videos.create') }}"><i class="fa fa-plus"></i> {{ __('global.create') }}</a>
                            <a class="btn btn-warning reorder" href="{{ route('admin.reorder', ['table' => 'videos'])}}"><i class="fa fa-align-left"></i> {{ __('global.reorder') }}</a>
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
                                    <th>{{ __('global.summary') }}</th>
                                    <th width="170" class="text-center">{{ __('global.publish_date') }}</th>
                                    <th width="220">{{ __('global.created_at') }}</th>
                                    <th width="200" class="text-center">{{ __('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($videos as $video)
                                <tr>
                                    <td class="text-center">{{ (($videos->currentPage() - 1) * $videos->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span @class(['fa' => true, 'fa-check text-success' => $video->is_active, 'fa-times text-danger' => !$video->is_active])></span>
                                    </td>
                                    <td class="text-center">
                                        <x-admin.remarks :object="$video" />
                                    </td>
                                    <td>{{ $video->title }}</td>
                                    <td>{{ Str::of($video->summary)->stripTags()->limit(50) }}</td>
                                    <td class="text-center">{{ $video->publish_date?->translatedFormat('d.m.Y.') }}</td>
                                    <td>{{ $video->created_at->translatedFormat('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.videos.edit',    ['video' => $video]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> {{ __('global.edit') }}</a>
                                        <a href="{{ route('admin.videos.destroy', ['video' => $video]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  {{ __('global.delete') }}</a>
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