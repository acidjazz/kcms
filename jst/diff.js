var Diff;

Diff = {
  result: false,
  i: function(data, result) {
    Diff.result = result;
    $('.diff > .body').html(data.html);
    _.on('.fade', '.diff');
    return Diff.handlers();
  },
  handlers: function() {
    return $('.diff > .actions > .action').click(Diff.action);
  },
  action: function() {
    var t;
    t = $(this);
    if (t.hasClass('cancel')) {
      Diff.result(false);
      Diff.d();
    }
    if (t.hasClass('confirm')) {
      Diff.result(true);
      return Diff.d();
    }
  },
  d: function() {
    _.off('.fade', '.diff');
    return $('.diff > .actions > .action').unbind('click', Diff.action);
  }
};
