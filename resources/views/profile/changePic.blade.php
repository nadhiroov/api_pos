<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="container">
    @csrf
    <div class="row">
        @for ($index = 1; $index < 13; $index++)
            <div class="col-6 col-sm-3 mb-3">
                <label class="avatar-option w-100">
                    <input type="radio" name="avatar" value="{{ $index }}" class="d-none">
                    <img src="{{ asset('assets/images/profile/user-' . $index . '.jpg') }}"
                        class="img-fluid avatar-img border rounded">
                </label>
            </div>
        @endfor
    </div>
</div>
