<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="tb-fname" placeholder="Enter Name here" name="name"
        value="{{ $user->name }}" readonly>
    <label for="tb-fname">Name</label>
</div>
<div class="mt-7 mb-3">
    <label class="form-label">Roles</label>
    <select class="select2 form-control" name="roles[]" multiple>
        <option></option>
        @foreach ($allRoles as $role)
            <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoleIds)) ? 'selected' : '' }}>
                {{ $role->role_name }}
            </option>
        @endforeach
    </select>
</div>
<input type="hidden" name="id" value="{{ $user->id }}">
