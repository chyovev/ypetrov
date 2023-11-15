{{-- localization variables & other javascript global admin settings --}}
<script type="text/javascript">
    // used during elements reordering
    const CSRF_TOKEN    = "{{ csrf_token() }}";

    const DELETE_PROMPT = "{{ __('global.delete_prompt') }}",
          REORDER       = "{{ __('global.reorder') }}",
          YES           = "{{ __('global.yes') }}",
          SUBMIT        = "{{ __('global.submit') }}",
          SUCCESS       = "{{ __('global.success') }}",
          ERROR         = "{{ __('global.error') }}";
          CANCEL        = "{{ __('global.cancel') }}";
</script>