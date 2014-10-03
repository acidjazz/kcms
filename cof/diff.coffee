Diff =

  result: false

  i: (data, result) ->

    Diff.result = result
    $('.diff > .body').html data.html

    _.on '.fade', '.diff'

    Diff.handlers()

  handlers: ->
    $('.diff > .actions > .action').click Diff.action

  action: ->

    t = $ this

    if t.hasClass 'cancel'
      Diff.result(false)
      Diff.d()

    if t.hasClass 'confirm'
      Diff.result(true)
      Diff.d()

  d: ->
    _.off '.fade', '.diff'
    $('.diff > .actions > .action').unbind  'click', Diff.action
