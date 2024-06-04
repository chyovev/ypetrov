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

                            <div class="form-group row @error('is_active') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="is_active">{{ __('global.public') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="hidden"   name="is_active" value="0" />
                                    <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $galleryImage->is_active)) />
                                    @error('is_active')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('title') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="title"> {{ __('global.title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" name="title" id="title" class="form-control input-default" value="{{ old('title', $galleryImage->title) }}" />
                                    @error('title')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <x-admin.upload :object="$galleryImage" />

                            <x-admin.form-submit-button />

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>