require('./bootstrap');


$(document).on('click', '.phone-button', function () {
  var button = $(this);
  axios.post(button.data('source')).then(function (response) {
    button.find('.number').html(response.data)
  }).catch(function (error) {
    console.error(error);
  });
});

$('.banner').each(function () {
  var block = $(this);
  var url = block.data('url');
  var format = block.data('format');
  var category = block.data('category');
  var region = block.data('region');

  axios
    .get(url, {params: {
        format: format,
        category: category,
        region: region
      }})
    .then(function (response) {
      block.html(response.data);
    })
    .catch(function (error) {
      console.error(error);
    });
});
