@php
$title = $galleryImage->exists ? __('global.edit')           : __('global.create');
$route = $galleryImage->exists ? 'admin.gallery_images.edit' : 'admin.gallery_images.create';
$param = $galleryImage->exists ? $galleryImage              : null;
@endphp
<x-admin.layout :$title :$route :$param>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary"><em class="fa fa-pencil-square-o"></em> {{ $title }}</h3>
                </div>
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
                                <label class="col-lg-3 col-form-label text-right" for="title"> {{ __('global.title') }} <span class="text-danger">*</span></label>
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
                                    <button type="submit" class="btn btn-primary"><em class="fa fa-save"></em> {{ __('global.submit') }}</button>
                                    <a href="{{ route('admin.gallery_images.index') }}" class="btn btn-inverse"><em class="fa fa-reply"></em> {{ __('global.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>