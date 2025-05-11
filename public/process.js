function confirmDelete(selection) {
    let id = $(selection).attr("data-id")
    let target = $(selection).attr("target")
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: target + "/" + id,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                dataType: "json",
                async: false,
                success: function (response = "") {
                    if (response.status == "Success") {
                        toastr.success(response.message, response.status);
                        $("#datatable").DataTable().ajax.reload(null, false);
                    } else {
                        if (response.code == 23000) {
                            Swal.fire({
                                type: "error",
                                title: response.status,
                                text: "The data is constrained with product",
                            });
                        } else {
                            Swal.fire({
                                type: "error",
                                title: response.status,
                                text: response.message,
                            });
                        }
                    }
                    $("#zero_config").DataTable().ajax.reload(null, false);
                },
                error: function (response) {
                    Swal.fire({
                        type: "error",
                        title: response.status,
                        text: response.message,
                    });
                },
            });
        }
    });
}
