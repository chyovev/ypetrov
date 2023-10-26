<!-- Success flash message -->
@if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show text-inverse">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h3><em class="fa fa-check"></em> Success</h3>
    {{ session()->get('success') }}
</div>
@endif

<!-- Errors flash message -->
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h3 class="text-danger"><em class="fa fa-times"></em> Error</h3>
    {!! implode('', $errors->all('<div>:message</div>')) !!}
</div>
@endif