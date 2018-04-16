function deleteProduct(obj) {
    var id = $(obj).parent().parent().find(".id").text();
    $.ajax({
        url:"/product/delete",
        method: "post",
        data: {"id": id},
        success:function(data) {
            $(obj).parent().parent().remove();
            alert("product deleted");
        },
        error: function(error) {
            alert(error);
        }
    });
}

function editProduct(obj) {
    var id = $(obj).parent().parent().find(".id").text();
    window.location = "/product/update?id="+id;
}

function updateProduct(obj) {
    var id = $(obj).parent().parent().find(".id").text();
    var name = $(obj).parent().parent().find(".name").text();
    var typeid = $(obj).parent().parent().find(".typeid").text();
    var price = $(obj).parent().parent().find(".price").text();
    var description = $(obj).parent().parent().find(".description").text();
    var picURL = $(obj).parent().parent().find(".product-image");

    var product = {"id" : id, "name" : name, "typeId" : typeid, "price" : price, "description" : description, "picURL" : picURL};

    $.ajax({
        url:"/product/update",
        method: "post",
        data: product,
        cache: false,
        contentType: false,
        processData: false,
        success:function(data) {
            alert("product updated")
        },
        error: function(error) {
            alert("an error occured while updating");
        }
    });
}