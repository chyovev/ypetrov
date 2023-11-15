@php
$title = $user->exists ? __('global.edit')  : __('global.create');
$route = $user->exists ? 'admin.users.edit' : 'admin.users.create';
$param = $user->exists ? $user              : null;
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
                        $action = $user->exists ? route('admin.users.update', ['user' => $user]) : route('admin.users.store')
                    @endphp
                        <form method="post" action="{{ $action }}">
                            @csrf

                            @if ($user->exists)
                                @method('PUT')
                            @endif

                            <div class="form-group row @error('name') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="val-name">{{ __('global.name') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="name" class="form-control input-default" value="{{ old('name', $user->name) }}" />
                                    @error('name')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('email') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="val-email">{{ __('global.email') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" name="email" class="form-control input-default" value="{{ old('email', $user->email) }}" />
                                    @error('email')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row @error('password') has-error has-feedback @enderror">
                                <label class="col-lg-3 col-form-label text-right" for="val-password">{{ __('global.password') }} <span class="text-danger @if ($user->exists) d-none @endif">*</span></label>
                                <div class="col-lg-8">
                                    <input type="password" name="password" class="form-control input-default" />
                                    @error('password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <x-admin.form-submit-button />

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>