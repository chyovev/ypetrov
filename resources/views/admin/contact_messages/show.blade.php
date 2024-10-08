<x-admin.layout route="admin.contact_messages.show" :param="$message">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary"><em class="fa fa-eye"></em> {{ __('global.preview') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-validation">

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label text-right">{{ __('global.created_at') }}</label>
                            <div class="col-lg-8 m-t-8">
                                <p class="form-control-static">{{ $message->created_at->translatedFormat('d.m.Y. @ H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label text-right">{{ __('global.country') }}</label>
                            <div class="col-lg-8 m-t-8">
                                <p class="form-control-static">
                                    <x-admin.flag :countryCode="$message->visitor->country_code" />
                                </p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label text-right">{{ __('global.name') }}</label>
                            <div class="col-lg-8 m-t-8">
                                <p class="form-control-static">{{ $message->name }}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label text-right">{{ __('global.email') }}</label>
                            <div class="col-lg-8 m-t-8">
                                <p class="form-control-static">
                                    @if ($message->email)
                                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                    @else
                                        &ndash;
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label text-right">{{ __('global.message') }}</label>
                            <div class="col-lg-8 m-t-8">
                                <p class="form-control-static">{!! nl2br(e($message->message)) !!}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-9 ml-auto">
                                <a href="{{ route('admin.contact_messages.index') }}" class="btn btn-inverse">{{ __('global.back') }}</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>