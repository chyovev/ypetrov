<x-admin.layout route="admin.gallery_images.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> {{ __('global.gallery') }}
                        <x-admin.total-results :items="$galleryImages" />
                        <div class="dt-buttons float-right ml-2">
                            <a class="btn btn-success" href="{{ route('admin.gallery_images.create') }}"><i class="fa fa-plus"></i> {{ __('global.create') }}</a>
                            <a class="btn btn-warning reorder" href="{{ route('admin.reorder', ['table' => 'gallery_images'])}}"><i class="fa fa-align-left"></i> {{ __('global.reorder') }}</a>
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
                                    <th>{{ __('global.created_at') }}</th>
                                    <th width="200" class="text-center">{{ __('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($galleryImages as $image)
                                <tr>
                                    <td class="text-center">{{ (($galleryImages->currentPage() - 1) * $galleryImages->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span @class(['fa' => true, 'fa-check text-success' => $image->is_active, 'fa-times text-danger' => !$image->is_active])></span>
                                    </td>
                                    <td class="text-center">
                                        <x-admin.remarks :object="$image" />
                                    </td>
                                    <td>
                                        @if ($image->getImageURL())
                                            <a href="{{ $image->getImageURL() }}" target="_blank"><img src="{{ $image->getImageThumbURL() }}" width="20" class="img-rounded" /></a>
                                        @endif
                                        {{ Str::of($image->title)->stripTags()->limit(70) }}
                                    </td>
                                    <td>{{ $image->created_at->translatedFormat('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.gallery_images.edit',    ['gallery_image' => $image]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> {{ __('global.edit') }}</a>
                                        <a href="{{ route('admin.gallery_images.destroy', ['gallery_image' => $image]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  {{ __('global.delete') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <x-admin.pagination :data="$galleryImages" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>