<!-- Success flash message -->
@if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    {{ session()->get('success') }}
</div>
@endif

<!-- Errors flash message -->
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    {!! implode('<br />', $errors->all('<div>:message</div>')) !!}
</div>
@endif