<x-admin.authentication.layout>
    <form method="post" action="{{ route('admin.login') }}">
        @csrf

        <div class="form-group @error('email') has-error has-feedback @enderror">
            <input type="text" name="email" class="form-control" placeholder="{{ __('global.email') }}" autofocus="true" />

            @error('email')
                <span class="ti-close form-control-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group @error('password') has-error has-feedback @enderror">
            <input type="password" name="password" class="form-control" placeholder="{{ __('global.password') }}" />

            @error('password')
                <span class="ti-close form-control-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember" value="1" /> {{ __('global.remember_me') }}
            </label>

            <label class="pull-right">
                <a href="#">{{ __('global.forgotten_password') }}</a>
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-flat m-t-30">{{ __('global.log_in') }}</button>
    </form>
</x-admin.authentication.layout>
