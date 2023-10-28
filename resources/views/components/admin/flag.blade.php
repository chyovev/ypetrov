@if (isset($countryCode) && $countryCode)
    <span class="flag-icon-background flag-icon flag-icon-{{ Str::of($countryCode)->lower() }}" title="{{ $countryCode }}"></span>
@else
    &ndash;
@endif