Kcms =

  i: ->
    console.log 'Kcms.i()'
    Kcms.handlers()

  handlers: ->

    $('#form').submit Kcms.submit

    console.log 'Kcms.handlers()'

  submit: ->

    data = {}

    $('.field > .value > input, .field > .value > textarea').each (i, el) ->
      el = $ el
      key = el.attr 'name'
      value = el.val()
      data[key] = value
    .promise().done ->
      console.log data

      $.post '/update/', data
        .always ->
          console.log 'post complete'
        .success (response) ->
          console.log response
        .fail (response) ->
          console.log 'failure', response

    return false

