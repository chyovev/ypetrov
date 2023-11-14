<x-admin.authentication.layout>
    <form method="post" action="{{ route('password.reset') }}" class="position-relative">
        @csrf

        <div class="checkbox absolute-top-right">
            <label>
                <a href="{{ url()->route('admin.home') }}">{{ __('global.home') }}</a>
            </label>
        </div>

        <input type="hidden" name="token" value="{{ $token }}" />

        <h3 class="text-center m-b-20">
            <em class="fa fa-key fa-5x d-block"></em>
            {{ __('global.reset_password') }}
        </h3>

        <p class="text-center m-b-30 p-l-50 p-r-50">{{ __('global.reset_password_info') }}</p>

        @error('token')
            <div class="text-danger text-center m-b-20">{{ $message }}</div>
        @enderror

        <div class="form-group @error('email') has-error has-feedback @enderror">
            <input type="text" name="email" readonly="readonly" class="form-control" placeholder="{{ __('global.email') }}" value="{{ $email }}" />

            @error('email')
                <span class="ti-close form-control-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group @error('password') has-error has-feedback @enderror">
            <input type="password" name="password" class="form-control" placeholder="{{ __('global.password') }}" autofocus="true" />

            @error('password')
                <span class="ti-close form-control-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('global.password_confirmation') }}" />
        </div>

        <button type="submit" class="btn btn-primary btn-flat m-t-10">{{ __('global.change_password') }}</button>
    </form>
</x-admin.authentication.layout>
