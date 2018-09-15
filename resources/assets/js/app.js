require('./bootstrap');

$(document).on('click', '.phone-button', function () {
  var button = $(this);

  axios.post(button.data('source')).then(function (response) {
    button.find('.number').html(response.data)
  }).catch(function (error) {
      console.log(error);
  });
});
