@if ($object->exists)
<div class="card" id="comments">
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
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-trash"></i>
                            {{ __('global.delete') }}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <li><a class="confirm-delete" href="{{ route('admin.comments.destroy', ['comment' => $comment]) }}">{{ __('global.delete') }}</a></li>
                            <li><a class="confirm-delete" href="{{ route('admin.comments.destroy', ['comment' => $comment, 'ban' => 'true']) }}">{{ __('global.delete_and_ban') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="form-group-row text-center">{{ __('global.no_comments') }}</div>
        @endforelse
    </div>
</div>
@endif