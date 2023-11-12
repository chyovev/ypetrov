<x-admin.layout title="Gallery" route="admin.gallery_images.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> Gallery
                        <div class="dt-buttons float-right">
                            <a class="btn btn-success" href="{{ route('admin.gallery_images.create') }}"><i class="fa fa-plus"></i> Add</a>
                        </div>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center" width="90">Remarks</th>
                                    <th>Title</th>
                                    <th>Created at</th>
                                    <th width="150" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($galleryImages as $image)
                                <tr>
                                    <td class="text-center">{{ (($galleryImages->currentPage() - 1) * $galleryImages->perPage()) + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <x-admin.remarks :object="$image" />
                                    </td>
                                    <td>{{ $image->title }}</td>
                                    <td>{{ $image->created_at->format('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.gallery_images.edit',    ['gallery_image' => $image]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> Edit</a>
                                        <a href="{{ route('admin.gallery_images.destroy', ['gallery_image' => $image]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="m-t-30">{{ $galleryImages->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>