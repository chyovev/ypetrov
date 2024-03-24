@if ($object->exists)
<div class="card">
    <div class="card-title">
        <h3 class="text-primary"><em class="fa fa-comments"></em> {{ __('global.comments') }}</h3>
    </div>
    <div class="card-body">

        @forelse ($object->comments->reverse() as $comment)
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">
                    <x-admin.flag :countryCode="$comment->visitor->country_code" />

                    {{ $comment->name }}
                </label>
                <div class="col-lg-7 p-t-5">
                    <span class="underline-dashed">{{ $comment->created_at->translatedFormat('d.m.Y. @ H:i:s') }}</span>

                    <p class="form-control-static m-t-20">
                        {!! nl2br(e($comment->message)) !!}
                    </p>
                </div>
                
                <div class="col-lg-1 text-right">
                    <a href="{{ route('admin.comments.destroy', ['comment' => $comment]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i> {{ __('global.delete') }}</a>
                </div>
            </div>
        @empty
            <div class="form-group-row text-center">{{ __('global.no_comments') }}</div>
        @endforelse
    </div>
</div>
@endif