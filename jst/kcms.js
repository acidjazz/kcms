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
  data: function(complete) {
    var data;
    data = {};
    return $('.field > .value > input, .field > .value > textarea').each(function(i, el) {
      var key, value;
      el = $(el);
      key = el.attr('name');
      value = el.val();
      return data[key] = value;
    }).promise().done(function() {
      return complete(data) != null;
    });
  },
  diff: function(data, result) {
    return Kcms.data(function(data) {
      return $.post('/diff/', data).always(function() {
        return console.log('diff post complete');
      }).success(function(response) {
        if (Object.keys(response.data.diff).length === 0) {
          return Status.i(false, 'No changes found', 3);
        } else {
          return Diff.i(response.data, function(result) {
            if (result) {
              Status.i(true, 'Updating..');
              return Kcms.update();
            } else {
              Status.i(false, 'Changes Reverted', 3);
              return $('#form')[0].reset();
            }
          });
        }
      }).fail(function(response) {
        return console.log('failure', response);
      });
    });
  },
  submit: function() {
    Kcms.data(function(data) {
      return Kcms.diff(data, function(diff) {
        return console.log('diff', diff);
      });
    });
    return false;
  },
  update: function() {
    Kcms.data(function(data) {
      return $.post('/update/', data).always(function() {
        return Status.i(true, 'Updated', 3);
      }).success(function(response) {
        return console.log(response);
      }).fail(function(response) {
        return console.log('failure', response);
      });
    });
    return false;
  }
};
