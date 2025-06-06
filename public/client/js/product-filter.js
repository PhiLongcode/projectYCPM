$(function () {
      $('.arrange-price').change(function () {
            let url = $(this).data('url');
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let option = $(this).val();

            $.ajax({
                  url: url,
                  method: 'GET',
                  data: { option: option, _token: csrfToken },
                  dataType: 'json',
                  success: function (response) {
                        // console.log('a');
                        $('#listProduct').html(response);

                  },
                  error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                  }
            });
      });
});
