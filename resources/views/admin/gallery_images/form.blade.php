@php
$title = $galleryImage->exists ? 'Update image'              : 'Add image';
$route = $galleryImage->exists ? 'admin.gallery_images.edit' : 'admin.gallery_images.create';
$param = $galleryImage->exists ? $galleryImage              : null;
@endphp
<x-admin.layout :$title :$route :$param>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                    @php
                        $action = $galleryImage->exists ? route('admin.gallery_images.update', ['gallery_image' => $galleryImage]) : route('admin.gallery_images.store')
                    @endphp
                        <form method="post" action="{{ $action }}" enctype="multipart/form-data">
                            @csrf

                            @if ($galleryImage->exists)
                                @method('PUT')
                            @endif

                            <div class="form-group row @error('title') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="title">Title <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="title" id="title" class="form-control input-default" value="{{ old('title', $galleryImage->title) }}" />
                                    @error('title')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <x-admin.upload :object="$galleryImage" />

                            <div class="form-group row">
                                <div class="col-lg-9 ml-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('admin.gallery_images.index') }}" class="btn btn-inverse">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>