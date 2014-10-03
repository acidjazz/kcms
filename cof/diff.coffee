Diff =

  result: false

  i: (data, result) ->

    Diff.result = result

    Diff.populate data

    _.on '.fade', '.diff'

    Diff.handlers()

  handlers: ->

    $('.diff > .actions > .action').click Diff.action

  action: ->

    t = $ this

    console.log t.attr 'class'

    if t.hasClass 'cancel'
      Diff.result(false)
      Diff.d()

    if t.hasClass 'confirm'
      Diff.result(true)
      Diff.d()

  populate: (data) ->
    console.log data
    body = $('.diff > .body')

  d: ->
    _.off '.fade', '.diff'
    $('.diff > .actions > .action').unbind, 'click', Diff.action
