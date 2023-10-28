@php
$title = $poem->exists ? 'Update poem'      : 'Create poem';
$route = $poem->exists ? 'admin.poems.edit' : 'admin.poems.create';
$param = $poem->exists ? $poem              : null;
@endphp
<x-admin.layout :$title :$route :$param>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                    @php
                        $action = $poem->exists ? route('admin.poems.update', ['poem' => $poem]) : route('admin.poems.store')
                    @endphp
                        <form method="post" action="{{ $action }}">
                            @csrf

                            @if ($poem->exists)
                                @method('PUT')
                            @endif

                            <div class="form-group row @error('is_active') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="is_active">Public <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="hidden"   name="is_active" value="0" />
                                    <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $poem->is_active)) />
                                    @error('is_active')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('title') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="title">Title <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="title" id="title" class="form-control input-default" value="{{ old('title', $poem->title) }}" />
                                    @error('title')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('slug') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="slug">URL identificator <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="slug" id="slug" class="form-control input-default" value="{{ old('slug', $poem->slug) }}" />
                                    @error('slug')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('dedication') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="dedication">Dedication</label>
                                <div class="col-lg-8">
                                    <input type="text" name="dedication" id="dedication" class="form-control input-default" value="{{ old('dedication', $poem->dedication) }}" />
                                    @error('dedication')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('use_monospace_font') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="use_monospace_font">Monospace <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="hidden"   name="use_monospace_font" value="0" />
                                    <input type="checkbox" name="use_monospace_font" value="1" id="use_monospace_font" @checked(old('use_monospace_font', $poem->use_monospace_font)) />
                                    @error('use_monospace_font')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('text') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="chartdiv3">Text <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <textarea name="text" id="chartdiv3" class="textarea_editor form-control" rows="15">{!! old('text', $poem->text) !!}</textarea>
                                    @error('text')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- When editing a poem, if it's associated
                                 with any books, list them statically below -->
                            @if ($poem->exists && $poem->books->count())
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label text-right">Published in</label>
                                <div class="col-lg-8 m-t-8">
                                    @foreach ($poem->books as $book)
                                        <p class="form-control-static">{{ $book->title }} ({{ $book->publish_year }})</p>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-lg-9 ml-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('admin.poems.index') }}" class="btn btn-inverse">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>