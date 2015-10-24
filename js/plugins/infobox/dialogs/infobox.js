CKEDITOR.dialog.add('infobox', function (editor) {
  return {
    title: editor.config.infobox.strings.dialogTitle,
    minWidth: 200,
    minHeight: 100,
    contents: [
      {
        id: 'info',
        elements: [
          {
            id: 'class',
            type: 'select',
            label: editor.config.infobox.strings.classSelectorLabel,
            items: editor.config.infobox.classes,
            setup: function (widget) {
              this.setValue(widget.data.infoboxClass);
            },
            commit: function (widget) {
              widget.setData('infoboxClass', this.getValue());
            }
          }
        ]
      }
    ]
  };
});
