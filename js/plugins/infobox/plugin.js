CKEDITOR.plugins.add('infobox', {

  requires: 'widget,dialog',

  icons: 'infobox',

  beforeInit: function( editor ) {
    editor._.infobox = {'sdf': 'sdf'};
  },

  init: function (editor) {

    CKEDITOR.dialog.add('infobox', this.path + 'dialogs/infobox.js');
    editor.ui.addButton && editor.ui.addButton('infobox', {
      label: editor.config.infobox.strings.buttonLabel,
      command: 'infobox'
    });

    editor.widgets.add('infobox', {

      allowedContent: 'div(!infobox);div(!infobox-content);h2(!infobox-title)',

      requiredContent: 'div(infobox)',

      editables: {
        title: {
          selector: '.infobox-title',
          allowedContent: 'strong em'
        },
        content: {
          selector: '.infobox-content',
          allowedContent: 'p br ul ol li strong em'
        }
      },

      template: '<div class="infobox"><h2 class="infobox-title"></h2><div class="infobox-content"></div></div>',

      dialog: 'infobox',

      upcast: function (element) {
        return element.name == 'div' && element.hasClass('infobox');
      },

      init: function () {
        var classes = this.element.getAttribute('class').split(' ');
        for (var i = 0; i < classes.length; i++) {
          if (classes[i].indexOf('infobox--') === 0) {
            this.setData('infoboxClass', classes[i].substr(9));
          }
        }
      },

      data: function () {
        // Remove any of "infobox--" classes.
        var classes = this.element.getAttribute('class').split(' ');
        for (var i = 0; i < classes.length; i++) {
          if (classes[i].indexOf('infobox--') === 0) {
            this.element.removeClass(classes[i]);
          }
        }
        // Add new one.
        this.element.addClass('infobox--' + this.data.infoboxClass);
      }

    });
  }
});
