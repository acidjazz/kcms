var Status;

Status = {
  el: false,
  timeout: false,
  i: function(type, message, timeout) {
    if (Status.el === false) {
      Status.el = $('.status');
      $(document).on('click', '.status > .close', Status.d);
    }
    if (type) {
      Status.el.addClass('success').removeClass('error');
    } else {
      Status.el.addClass('error').removeClass('success');
    }
    Status.el.find('.copy').html(message);
    _.on(Status.el);
    if (timeout && !isNaN(timeout)) {
      return Status.timeout = setTimeout(function() {
        return Status.d();
      }, timeout * 1000);
    }
  },
  d: function() {
    _.off(Status.el);
    if (Status.timeout) {
      return clearTimeout(Status.timeout);
    }
  }
};
