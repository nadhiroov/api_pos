<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="mt-7 mb-3">
    <label class="form-label">Branches</label>
    <select class="select2 form-control" name="branches_id[]" multiple>
        <option></option>
        @foreach ($data as $branch)
            <option @if($branch->user_id != null && in_array($user_id, $branch->user_id)) selected @endif value="{{ $branch->id }}">{{ $branch->name }}</option>
        @endforeach
    </select>
</div>
<input type="hidden" name="id" value="{{ $user_id }}">