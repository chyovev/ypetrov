<div class="table-responsive">
    <table class="display nowrap table table-condensed table-hover table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>File</th>
                <th>Created at</th>
                <th width="160" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attachments as $attachment)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $attachment->original_file_name }}</td>
                <td>{{ $attachment->created_at->format('d.m.Y. @ H:i:s') }}</td>
                <td>
                    <a href="{{ $attachment->getURL() }}" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</a>
                    <a href="{{ route('admin.attachments.destroy', ['attachment' => $attachment]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i> Delete</a>
                </td>
            </tr>
            @endforeach
    </table>
</div>