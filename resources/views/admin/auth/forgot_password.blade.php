<x-admin.authentication.layout>
    <form method="post" action="{{ route('admin.forgot_password') }}">
        @csrf

        <h3 class="text-center"><em class="fa fa-lock fa-5x"></em></h3>

        <div class="form-group @error('email') has-error has-feedback @enderror">
            <input type="text" name="email" class="form-control" placeholder="{{ __('global.email') }}" autofocus="true" />

            @error('email')
                <span class="ti-close form-control-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-flat m-t-30">{{ __('global.reset_password') }}</button>
    </form>
</x-admin.authentication.layout>
