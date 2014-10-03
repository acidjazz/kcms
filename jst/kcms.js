var Kcms;

Kcms = {
  i: function() {
    console.log('Kcms.i()');
    return Kcms.handlers();
  },
  handlers: function() {
    $('#form').submit(Kcms.submit);
    return console.log('Kcms.handlers()');
  },
  submit: function() {
    var data;
    data = {};
    $('.field > .value > input, .field > .value > textarea').each(function(i, el) {
      var key, value;
      el = $(el);
      key = el.attr('name');
      value = el.val();
      return data[key] = value;
    }).promise().done(function() {
      console.log(data);
      return $.post('/update/', data).always(function() {
        return console.log('post complete');
      }).success(function(response) {
        return console.log(response);
      }).fail(function(response) {
        return console.log('failure', response);
      });
    });
    return false;
  }
};
