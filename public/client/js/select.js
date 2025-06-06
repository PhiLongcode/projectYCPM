$(function () {
    $('#cat_id').change(function () {
        var cat_id = $(this).val();
        if (cat_id !== '') {
            // Đường dẫn đến file xử lý ajax 
            var URL = $(this).data('url');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: URL,
                method: 'post',
                data: { cat_id: cat_id, _token: csrfToken },
                dataType: 'json',
                success: function (data) {
                      var select = $('#cat_product');
                      select.empty();
                      select.append($('<option>', {
                          value: '',
                          text: '-- Chọn loại sản phẩm --'
                      }));
                      $.each(data[0], function (i, cat) {
                          select.append($('<option>', {
                              value: cat['cat_product'],
                              text: cat['cat_product_name']
                          }));
                      });
                    // console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr);
                    console.log("b");
                }
            });
        }
    });
});
