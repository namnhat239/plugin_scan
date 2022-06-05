;(function ($) {
  var OpinionStageDialogApp = {
    cacheElements: function () {
      this.cache = {
        $deactivateLink: $('#the-list').find('[data-slug="social-polls-by-opinionstage"] span.deactivate a'),
        $modal: $('#opinionistage-deactivate-feedback-modal'),
        $skipButton: $('#opinionstage-dialog-skip'),
        $closeButton: $('#opinionstage-dialog-close'),
        $submitButton: $('#opinionstage-dialog-submit'),
        $dialogForm: $('#opinionstage-deactivate-feedback-dialog-form')
      }
    },
    deactivate: function () {
      location.href = this.cache.$deactivateLink.attr('href')
    },
    bindEvents: function () {
      var self = this
      self.cache.$deactivateLink.on('click', function (e) {
        e.preventDefault()
        self.cache.$modal.fadeIn()
      })

      self.cache.$modal.on('click', function (e) {
        if ($(e.target).is(self.cache.$modal)) {
          self.cache.$modal.fadeOut()
        }
      })
      self.cache.$closeButton.on('click', function (e) {
        self.cache.$modal.fadeOut()
      })

      self.cache.$skipButton.on('click', function (e) {
        self.deactivate()
      })
      self.cache.$submitButton.on('click', function (e) {
        e.preventDefault()
        self.sendFeedback()
        $(this).addClass('opinionstage-loading')
      })
    },
    sendFeedback: function () {
      var self = this
      var formData = self.cache.$dialogForm.serialize()
      $.post(ajaxurl, formData, this.deactivate.bind(this))
    },
    init: function () {
      this.cacheElements()
      this.bindEvents()
    }
  }

  $(function () {
    OpinionStageDialogApp.init()
  })

}(jQuery))
