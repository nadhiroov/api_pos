<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="mt-7 mb-3">
    <label class="form-label">Roles</label>
    <select class="select2 form-control" name="branches_id[]" multiple>
        <option></option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" @if(in_array($role->id, $userRoles->roles->pluck('id')->toArray())) selected @endif>
                {{ $role->role_name }}
        @endforeach
    </select>
</div>
<input type="hidden" name="id" value="{{ $userRoles->id }}">