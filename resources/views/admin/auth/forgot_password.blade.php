<x-admin.authentication.layout>
    <form method="post" action="{{ route('admin.forgot_password') }}" class="position-relative">
        @csrf

        <div class="checkbox absolute-top-right">
            <label>
                <a href="{{ url()->previous() }}">{{ __('global.back') }}</a>
            </label>
        </div>

        <h3 class="text-center m-b-20">
            <em class="fa fa-lock fa-5x d-block"></em>
            {{ __('global.forgotten_password') }}
        </h3>

        <p class="text-center m-b-30 p-l-50 p-r-50">{{ __('global.forgotten_password_info') }}</p>

        <div class="form-group @error('email') has-error has-feedback @enderror">
            <input type="text" name="email" class="form-control" placeholder="{{ __('global.email') }}" autofocus="true" />

            @error('email')
                <span class="ti-close form-control-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-flat m-t-10">{{ __('global.reset_password') }}</button>
    </form>
</x-admin.authentication.layout>
