@php
$title = $pressArticle->exists ? 'Update press article'      : 'Create press article';
$route = $pressArticle->exists ? 'admin.press_articles.edit' : 'admin.press_articles.create';
$param = $pressArticle->exists ? $pressArticle              : null;
@endphp
<x-admin.layout :$title :$route :$param>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                    @php
                        $action = $pressArticle->exists ? route('admin.press_articles.update', ['press_article' => $pressArticle]) : route('admin.press_articles.store')
                    @endphp
                        <form method="post" action="{{ $action }}">
                            @csrf

                            @if ($pressArticle->exists)
                                @method('PUT')
                            @endif

                            <div class="form-group row @error('is_active') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="is_active">Public <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="hidden"   name="is_active" value="0" />
                                    <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $pressArticle->is_active)) />
                                    @error('is_active')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('title') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="title">Title <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="title" id="title" class="form-control input-default" value="{{ old('title', $pressArticle->title) }}" />
                                    @error('title')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('slug') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="slug">URL identificator <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="slug" id="slug" class="form-control input-default" value="{{ old('slug', $pressArticle->slug) }}" />
                                    @error('slug')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('text') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="chartdiv3">Text <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <textarea name="text" id="chartdiv3" class="textarea_editor form-control" rows="15">{!! old('text', $pressArticle->text) !!}</textarea>
                                    @error('text')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-9 ml-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('admin.press_articles.index') }}" class="btn btn-inverse">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>