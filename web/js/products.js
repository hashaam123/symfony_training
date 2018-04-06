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