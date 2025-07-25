<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-7">Image</h4>
        <form action="/shop/uploadImage" class="dropzone dz-clickable mb-2" id="my-dropzone2">
            @csrf
            <div class="dz-default dz-message">
                <button class="dz-button" type="button">Drop Thumbnail here
                    to upload</button>
            </div>
        </form>
        <p class="fs-2 text-center mb-0">
            Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted.
        </p>
    </div>
</div>
<form action="/shop/{{ $content->data }}" method="POST" class="form-process-add">
    @csrf
    <input type="hidden" name="image" id="shopImage" value="">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your shop name"
            required>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" placeholder="Enter your shop address"
            required>
    </div>
    <div class="mb-3">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="phone_number" name="phone"
            placeholder="Enter your phone number" required>
    </div>
