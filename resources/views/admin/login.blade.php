<x-admin.authentication.layout>
    <form method="post" action="{{ route('admin.login') }}">
        @csrf

        <div class="form-group @error('email') has-error has-feedback @enderror">
            <input type="text" name="email" class="form-control" placeholder="Email" autofocus="true" />

            @error('email')
                <span class="ti-close form-control-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group @error('password') has-error has-feedback @enderror">
            <input type="password" name="password" class="form-control" placeholder="Password" />

            @error('password')
                <span class="ti-close form-control-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember" value="1" /> Remember Me
            </label>

            <label class="pull-right">
                <a href="#">Forgotten Password?</a>
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-flat m-t-30">Log in</button>
    </form>
</x-admin.authentication.layout>
