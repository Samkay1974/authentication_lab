$(document).ready(function () {
    loadProducts();

    function loadProducts() {
        $.ajax({
            url: "../actions/fetch_product_action.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let container = $("#productContainer");
                container.empty();

                for (const brand in data) {
                    container.append(`<h3 class="text-primary mt-4">${brand}</h3>`);
                    const categories = data[brand];
                    for (const cat in categories) {
                        container.append(`<h5 class="text-secondary mt-2">${cat}</h5><div class="row" id="${brand}-${cat}"></div>`);
                        const section = $(`#${brand}-${cat}`);
                        categories[cat].forEach(p => {
                            section.append(`
                              <div class="col-md-4 mb-3">
                                <div class="card shadow-sm">
                                  <img src="${p.product_image}" class="card-img-top" alt="${p.product_title}">
                                  <div class="card-body">
                                    <h5>${p.product_title}</h5>
                                    <p>${p.product_desc}</p>
                                    <p><strong>$${p.product_price}</strong></p>
                                  </div>
                                </div>
                              </div>
                            `);
                        });
                    }
                }
            },
            error: () => Swal.fire("Error", "Failed to fetch products.", "error")
        });
    }

    $("#productForm").submit(function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: "../actions/add_product_action.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                const response = JSON.parse(res);
                Swal.fire(response.status, response.message, response.status);
                if (response.status === "success") {
                    $("#productForm")[0].reset();
                    loadProducts();
                }
            },
            error: () => Swal.fire("Error", "Could not add product.", "error")
        });
    });
});
