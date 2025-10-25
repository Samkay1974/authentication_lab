// js/product.js
$(function () {
  // init function that is safe to call once
  function initProductHandlers() {
    // Toggle add form button
    $("#showAddFormBtn").off("click").on("click", function () {
      $("#addProductForm").slideToggle(200, function () {
        if ($(this).is(":visible")) {
          $("html,body").animate({ scrollTop: $(this).offset().top - 20 }, 300);
        }
      });
    });

    // Submit add product form (idempotent binding)
    $("#productForm").off("submit").on("submit", function (e) {
      e.preventDefault();
      const $form = $(this);
      const $submit = $("#productFormSubmit");
      // basic validation
      const title = $.trim($form.find('[name="product_title"]').val() || '');
      const price = parseFloat($form.find('[name="product_price"]').val() || 0);
      const cat = $form.find('[name="product_cat"]').val();
      const brand = $form.find('[name="product_brand"]').val();
      const desc = $.trim($form.find('[name="product_desc"]').val() || '');
      if (!title || !price || price <= 0 || !cat || !brand || !desc) {
        Swal.fire("Error", "Please fill all required fields and provide a valid price.", "error");
        return;
      }

      // disable submit to prevent double clicks
      $submit.prop("disabled", true).addClass("disabled");

      const fd = new FormData(this);

      $.ajax({
        url: "../actions/add_product_action.php",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
          if (resp && resp.status === "success") {
            Swal.fire("Success", resp.message || "Product added", "success").then(function () {
              // either reload or insert the new product into DOM
              // simplest: reload to reflect backend state
              location.reload();
            });
          } else {
            Swal.fire("Error", (resp && resp.message) ? resp.message : "Could not add product", "error");
          }
        },
        error: function (xhr) {
          console.error("Add product error:", xhr.responseText || xhr.statusText);
          Swal.fire("Error", "Server error. Check console for details.", "error");
        },
        complete: function () {
          $submit.prop("disabled", false).removeClass("disabled");
        }
      });
    });

    // Handle Edit Button Click
$(document).off("click", ".editBtn").on("click", ".editBtn", function (e) {
      e.preventDefault();
      const product = $(this).data();

    $("#edit_product_id").val(product.id);
    $("#edit_product_title").val(product.title);
    $("#edit_product_price").val(product.price);
    $("#edit_product_desc").val(product.desc);
    $("#edit_product_keywords").val(product.keywords);
    $("#edit_product_cat").val(product.cat);
    $("#edit_product_brand").val(product.brand);

    $("#editProductModal").modal("show");
});

// Handle Edit Form Submit

$("#editProductForm").submit(function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
        url: "../actions/update_product_action.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                Swal.fire("Updated!", response.message, "success").then(() => {
                    location.reload();
                });
            } else {
                Swal.fire("Error", response.message, "error");
            }
        },
        error: function (xhr) {
            console.error("Update error:", xhr.responseText);
            Swal.fire("Error", "Could not update product.", "error");
        }
    });
});


    // Delegated Edit button handler
//  $(document).on("click", ".editBtn", function (e) {
//   e.preventDefault();
//   const id = $(this).data("id");

//   Swal.fire({
//     title: "Edit Product Title",
//     input: "text",
//     inputLabel: "Enter new product title",
//     showCancelButton: true,
//     confirmButtonText: "Update"
//   }).then((result) => {
//     if (result.isConfirmed) {
//       const newTitle = result.value.trim();
//       if (!newTitle) {
//         Swal.fire("Error", "Title cannot be empty.", "error");
//         return;
//       }

//       $.ajax({
//         url: "../actions/update_product_action.php",
//         type: "POST",
//         data: { product_id: id, product_title: newTitle },
//         dataType: "json",
//         success: function (response) {
//           if (response.status === "success") {
//             Swal.fire("Updated!", response.message, "success");
//             // Optionally refresh product list
//             location.reload();
//           } else {
//             Swal.fire("Error", response.message, "error");
//           }
//         },
//         error: function (xhr) {
//           console.error(xhr.responseText);
//           Swal.fire("Error", "Could not update product.", "error");
//         }
//       });
//     }
//   });
// });

    // Delegated Delete handler
    $(document).off("click", ".deleteBtn").on("click", ".deleteBtn", function (e) {
      e.preventDefault();
      const id = $(this).data("id");
      if (!id) return;
      Swal.fire({
        title: "Delete product?",
        text: "This will permanently delete the product.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete"
      }).then(function (res) {
        if (res.isConfirmed) {
          $.ajax({
            url: "../actions/delete_product_action.php",
            type: "POST",
            data: { product_id: id },
            dataType: "json",
            success: function (resp) {
              if (resp && resp.status === "success") {
                Swal.fire("Deleted", resp.message || "Product deleted", "success").then(function () {
                  // remove card from DOM without reload
                  $(`[data-product-card="${id}"]`).fadeOut(300, function () { $(this).remove(); });
                });
              } else {
                Swal.fire("Error", (resp && resp.message) ? resp.message : "Could not delete", "error");
              }
            },
            error: function (xhr) {
              console.error("Delete error:", xhr.responseText || xhr.statusText);
              Swal.fire("Error", "Server error. Check console.", "error");
            }
          });
        }
      });
    });
  }

// initialize
  initProductHandlers();
});
