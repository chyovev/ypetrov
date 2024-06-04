@if ($object->exists)
<div class="card" id="stats">
    <div class="card-title">
        <h3 class="text-primary"><em class="fa fa-bar-chart"></em> {{ __('global.stats') }}</h3>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label text-right">{{ __('global.reads') }}</label>
            <p class="form-control-static m-t-8">
                {{ $object->getTotalImpressions() }}
            </p>
        </div>
        
        <div class="form-group row">
            <label class="col-lg-3 col-form-label text-right">{{ __('global.likes') }}</label>
            <p class="form-control-static m-t-8">
                {{ $object->getTotalLikes() }}
            </p>
        </div>
    </div>
</div>
@endif