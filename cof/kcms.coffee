Kcms =

  i: ->
    console.log 'Kcms.i()'
    Kcms.handlers()

  handlers: ->

    $('#form').submit Kcms.submit

    console.log 'Kcms.handlers()'

  data: (complete) ->

    data = {}

    $('.field > .value > input, .field > .value > textarea').each (i, el) ->
      el = $ el
      key = el.attr 'name'
      value = el.val()
      data[key] = value
    .promise().done ->
      complete(data)?

  diff: (data, result) ->

    Kcms.data (data) ->
      $.post '/diff/', data
        .always ->
          console.log 'diff post complete'
        .success (response) ->

          if Object.keys(response.data).length is 0
            Status.i false, 'No changes found', 3
          else
            Diff.i response.data, (result) ->
              if result
                Status.i true, 'Updating..'
                Kcms.update()
              else
                Status.i false, 'Changes Reverted', 3
                $('#form')[0].reset()

        .fail (response) ->
          console.log 'failure', response

  submit: ->

    Kcms.data (data) ->
      Kcms.diff data, (diff) ->
        console.log 'diff', diff
    return false

  update: ->

    Kcms.data (data) ->
      $.post '/update/', data
        .always ->
          Status.i true, 'Updated', 3
        .success (response) ->
          console.log response
        .fail (response) ->
          console.log 'failure', response

    return false

