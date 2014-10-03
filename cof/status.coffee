Status =

  el: false
  timeout: false
  
  i: (type, message, timeout) ->

    if Status.el is false
      Status.el = $ '.status'
      $(document).on 'click', '.status > .close', Status.d

    if type
      Status.el
        .addClass 'success'
        .removeClass 'error'
    else
      Status.el
        .addClass 'error'
        .removeClass 'success'

    Status.el.find('.copy').html message

    _.on Status.el

    if timeout and !isNaN timeout
      Status.timeout = setTimeout ->
        Status.d()
      , timeout*1000

  d: ->
    _.off Status.el
    clearTimeout Status.timeout if Status.timeout

