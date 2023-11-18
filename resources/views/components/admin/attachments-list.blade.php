@if ($attachments->count())
<div class="table-responsive m-b-30 m-t-10">
    <table class="display nowrap table table-condensed table-hover table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>{{ __('global.file') }}</th>
                <th>{{ __('global.mime_type') }}</th>
                <th>{{ __('global.created_at') }}</th>
                <th width="190" class="text-center">{{ __('global.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attachments as $attachment)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $attachment->original_file_name }}</td>
                <td>{{ $attachment->mime_type }}</td>
                <td>{{ $attachment->created_at->format('d.m.Y. @ H:i:s') }}</td>
                <td>
                    <a href="{{ $attachment->getURL() }}" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> {{ __('global.view') }}</a>
                    <a href="{{ route('admin.attachments.destroy', ['attachment' => $attachment]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i> {{ __('global.delete') }}</a>
                </td>
            </tr>
            @endforeach
    </table>
</div>
@endif