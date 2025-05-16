{{-- <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head> --}}
@csrf
<div class="mt-7 mb-3">
    <label class="form-label">Staffs</label>
    <select class="select2 form-control" name="user_id[]" multiple>
        <option></option>
        @foreach ($data as $staff)
            <option @if($branch->user_id != null && in_array($staff->id, $branch->user_id)) selected @endif value="{{ $staff->id }}">{{ $staff->name }}</option>
        @endforeach
    </select>
</div>
<input type="hidden" name="branch_id" value="{{ $branch->id }}">