@php
$title = $video->exists ? 'Update video'      : 'Create video';
$route = $video->exists ? 'admin.videos.edit' : 'admin.videos.create';
$param = $video->exists ? $video              : null;
@endphp
<x-admin.layout :$title :$route :$param>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                    @php
                        $action = $video->exists ? route('admin.videos.update', ['video' => $video]) : route('admin.videos.store')
                    @endphp
                        <form method="post" action="{{ $action }}" enctype="multipart/form-data">
                            @csrf

                            @if ($video->exists)
                                @method('PUT')
                            @endif

                            <div class="form-group row @error('is_active') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="is_active">Public <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="hidden"   name="is_active" value="0" />
                                    <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $video->is_active)) />
                                    @error('is_active')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('title') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="title">Title <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="title" id="title" class="form-control input-default" value="{{ old('title', $video->title) }}" />
                                    @error('title')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('slug') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="slug">URL identificator <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="slug" id="slug" class="form-control input-default" value="{{ old('slug', $video->slug) }}" />
                                    @error('slug')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('publish_date') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="publish_date">Publish date</label>
                                <div class="col-lg-8">
                                    @php
                                        $value   = old('publish_date', $video->publish_date);
                                        $preview = ($value instanceof \Carbon\Carbon) ? $value->format('d.m.Y.') : $value;
                                    @endphp
                                    <input type="text" name="publish_date" id="publish_date" class="form-control datepicker" value="{{ $preview }}" />
                                    @error('publish_date')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('summary') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="summary">Summary</label>
                                <div class="col-lg-8">
                                    <textarea name="summary" id="summary" class="input-default form-control" rows="5">{{ old('summary', $video->summary) }}</textarea>
                                    @error('summary')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <x-admin.upload :object="$video" />

                            <div class="form-group row">
                                <div class="col-lg-9 ml-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('admin.videos.index') }}" class="btn btn-inverse">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>