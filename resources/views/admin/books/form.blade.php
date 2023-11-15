@php
$title = $book->exists ? __('global.edit')  : __('global.create');
$route = $book->exists ? 'admin.books.edit' : 'admin.books.create';
$param = $book->exists ? $book              : null;
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
                        $action = $book->exists ? route('admin.books.update', ['book' => $book]) : route('admin.books.store')
                    @endphp
                        <form method="post" action="{{ $action }}" enctype="multipart/form-data">
                            @csrf

                            @if ($book->exists)
                                @method('PUT')
                            @endif

                            <div class="form-group row @error('is_active') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="is_active">{{ __('global.public') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="hidden"   name="is_active" value="0" />
                                    <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $book->is_active)) />
                                    @error('is_active')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('title') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="title"> {{ __('global.title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="title" id="title" class="form-control input-default" value="{{ old('title', $book->title) }}" />
                                    @error('title')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('slug') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="slug">{{ __('global.slug') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="slug" id="slug" class="form-control input-default" value="{{ old('slug', $book->slug) }}" />
                                    @error('slug')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('publisher') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="publisher">{{ __('global.publisher') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" name="publisher" id="publisher" class="form-control input-default" value="{{ old('publisher', $book->publisher) }}" />
                                    @error('publisher')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('publish_year') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="publish_year">{{ __('global.publish_year') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" name="publish_year" id="publish_year" class="form-control input-default datepicker" data-date-min-view-mode="2" data-date-max-view-mode="2" data-date-format="yyyy" value="{{ old('publish_year', $book->publish_year) }}" />
                                    @error('publish_year')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('poem_id') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right">{{ __('global.poems') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    {{-- when editing a book, use the current poem set --}}
                                    {{-- if the validation has failed though, use old values --}}
                                    @php
                                        $selectedIds = old('poem_id', $book->poems->pluck('id')->toArray());
                                    @endphp
                                    
                                    <select name="poem_id[]" class="multi-select" data-values-order="{{ implode(',', $selectedIds) }}" multiple>
                                        @foreach ($poems as $poem)
                                            <option value="{{ $poem->id }}" title="{{  Str::of($poem->text)->stripTags()->squish()->limit(100)->trim() }}" @selected(in_array($poem->id, $selectedIds))>{{ $poem->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('poem_id')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <x-admin.upload :object="$book" />

                            <x-admin.form-submit-button />

                        </form>
                    </div>
                </div>
            </div>

            <x-admin.comments :object="$book" />
        </div>
    </div>

</x-admin.layout>