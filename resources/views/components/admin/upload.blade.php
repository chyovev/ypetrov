
<div class="form-group row @error('attachments') has-error has-feedback @enderror">
    <label class="col-lg-3 col-form-label text-right" for="attachments">{{ __('global.attachments') }}</label>
    <div class="col-lg-8">
        <x-admin.attachments-list :attachments="$object->attachments" />

        <input name="attachments[]" id="attachments" class="m-t-10" type="file" multiple />

        @error('attachments')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>