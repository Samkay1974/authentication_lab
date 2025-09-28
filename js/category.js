$(document).ready(function () {

    function loadCategories() {
        $.ajax({
            url: "../actions/fetch_category_action.php",
            type: "GET",
            dataType: "json",
            success: function (categories) {
                let tbody = $("#categoryTableBody");
                tbody.empty();

                if (categories.length === 0) {
                    tbody.append("<tr><td colspan='3' class='text-center'>No categories found.</td></tr>");
                } else {
                    categories.forEach(function (cat) {
                        tbody.append(`
                            <tr>
                                <td>${cat.category_id}</td>
                                <td>${cat.category_name}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtn" data-id="${cat.category_id}" data-name="${cat.category_name}">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteBtn" data-id="${cat.category_id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            error: function () {
                Swal.fire("Error", "Failed to fetch categories.", "error");
            }
        });
    }
    loadCategories();

    // === CREATE CATEGORY ===
    $("#addCategoryForm").submit(function (e) {
        e.preventDefault();
        let category_name = $("#category_name").val().trim();

        if (category_name === "") {
            Swal.fire("Error", "Category name is required!", "error");
            return;
        }

        $.ajax({
            url: "../actions/add_category_action.php",
            type: "POST",
            data: { category_name: category_name },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire("Success", response.message, "success");
                    $("#addCategoryForm")[0].reset();
                    loadCategories();
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Could not add category.", "error");
            }
        });
    });

    // === UPDATE CATEGORY ===
    $(document).on("click", ".editBtn", function () {
        let category_id = $(this).data("id");
        let old_name = $(this).data("name");

        Swal.fire({
            title: "Edit Category",
            input: "text",
            inputValue: old_name,
            showCancelButton: true,
            confirmButtonText: "Update",
        }).then((result) => {
            if (result.isConfirmed) {
                let new_name = result.value.trim();
                if (new_name === "") {
                    Swal.fire("Error", "Category name cannot be empty!", "error");
                    return;
                }

                $.ajax({
                    url: "../actions/update_category_action.php",
                    type: "POST",
                    data: { category_id: category_id, category_name: new_name },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire("Success", response.message, "success");
                            loadCategories();
                        } else {
                            Swal.fire("Error", response.message, "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Could not update category.", "error");
                    }
                });
            }
        });
    });

    // === DELETE CATEGORY ===
    $(document).on("click", ".deleteBtn", function () {
        let category_id = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "This will delete the category permanently.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../actions/delete_category_action.php",
                    type: "POST",
                    data: { category_id: category_id },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire("Deleted!", response.message, "success");
                            loadCategories();
                        } else {
                            Swal.fire("Error", response.message, "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Could not delete category.", "error");
                    }
                });
            }
        });
    });
});
