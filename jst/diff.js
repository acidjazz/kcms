var Diff;

Diff = {
  i: function(data) {
    Diff.populate(data);
    _.on('.fade', '.diff');
    return Diff.handlers();
  },
  handlers: function() {
    return $('.diff > .actions > .action').on('click', Diff.action);
  },
  action: function() {
    var t;
    t = $(this);
    console.log(t.attr('class'));
    if (t.hasClass('cancel')) {
      return _.off('.fade', '.diff');
    }
  },
  populate: function(data) {
    var body;
    console.log(data);
    body = $('.diff > .body');
    return body.html(data.html);
  }
};
