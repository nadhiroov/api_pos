<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="tb-fname" placeholder="Enter Name here" name="name" value="{{ $data->name }}">
    <label for="tb-fname">Category</label>
    <input type="hidden" name="id" value="{{ $data->id }}">
</div>
<div class="form-group mb-4">
    <label for="exampleFormControlSelect1">Merchant</label>
    <select class="form-control select2" id="exampleFormControlSelect1" name="branch_id">
        <option>All Branches</option>
        @foreach ($branches as $branch)
            <option @if ($data->branch_id == $branch->id) selected @endif value="{{ $branch->id }}">{{ $branch->name }}</option>
        @endforeach
    </select>
    <small id="name" class="form-text text-muted">Ignore this if show to all branches</small>
</div>