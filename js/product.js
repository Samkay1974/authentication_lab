$(document).ready(function () {

    // Show/Hide Add Form
    $("#showAddFormBtn").click(function () {
        $("#addProductForm").slideToggle();
        $("html, body").animate({ scrollTop: $("#addProductForm").offset().top }, 500);
    });

    // Submit Add Product Form
    $("#productForm").submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "../actions/add_product_action.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire("Success", response.message, "success").then(() => location.reload());
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire("Error", "Could not add product.", "error");
            }
        });
    });
});
