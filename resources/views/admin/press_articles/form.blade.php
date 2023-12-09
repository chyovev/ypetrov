@php
$title = $pressArticle->exists ? __('global.edit')           : __('global.create');
$route = $pressArticle->exists ? 'admin.press_articles.edit' : 'admin.press_articles.create';
$param = $pressArticle->exists ? $pressArticle              : null;
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
                        $action = $pressArticle->exists ? route('admin.press_articles.update', ['press_article' => $pressArticle]) : route('admin.press_articles.store')
                    @endphp
                        <form method="post" action="{{ $action }}" enctype="multipart/form-data">
                            @csrf

                            @if ($pressArticle->exists)
                                @method('PUT')
                            @endif

                            <div class="form-group row @error('is_active') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="is_active">{{ __('global.public') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="hidden"   name="is_active" value="0" />
                                    <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $pressArticle->is_active)) />
                                    @error('is_active')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('title') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="title"> {{ __('global.title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="title" id="title" class="form-control input-default" value="{{ old('title', $pressArticle->title) }}" />
                                    @error('title')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('slug') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="slug">{{ __('global.slug') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="slug" id="slug" class="form-control input-default" value="{{ old('slug', $pressArticle->slug) }}" />
                                    @error('slug')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('press') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="press">{{ __('global.press') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="press" id="press" class="form-control input-default" value="{{ old('press', $pressArticle->press) }}" />
                                    @error('press')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('publish_date') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="publish_date">{{ __('global.publish_date') }}</label>
                                <div class="col-lg-8">
                                    @php
                                        $value   = old('publish_date', $pressArticle->publish_date);
                                        $preview = ($value instanceof \Carbon\Carbon) ? $value->format('d.m.Y.') : $value;
                                    @endphp
                                    <input type="text" name="publish_date" id="publish_date" class="form-control datepicker" value="{{ $preview }}" />
                                    @error('publish_date')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('text') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="chartdiv3">{{ __('global.text') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <textarea name="text" id="chartdiv3" class="textarea_editor form-control" rows="15">{!! old('text', $pressArticle->text) !!}</textarea>
                                    @error('text')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <x-admin.upload :object="$pressArticle" />

                            <x-admin.form-submit-button />

                        </form>
                    </div>
                </div>
            </div>

            <x-admin.comments :object="$pressArticle" />
        </div>
    </div>

</x-admin.layout>