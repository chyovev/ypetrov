<x-admin.authentication.layout>
    <form method="post" action="{{ route('password.reset') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}" />

        <h3 class="text-center"><em class="fa fa-key fa-5x"></em></h3>

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

        <button type="submit" class="btn btn-primary btn-flat m-t-30">{{ __('global.change_password') }}</button>
    </form>
</x-admin.authentication.layout>
