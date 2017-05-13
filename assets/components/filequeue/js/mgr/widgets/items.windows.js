fileQueue.window.CreateItem = function (config) {
  config = config || {};
  if (!config.id) {
    config.id = 'filequeue-item-window-create';
  }
  Ext.applyIf(config, {
    title: _('filequeue_item_create'),
    width: 550,
    autoHeight: true,
    url: fileQueue.config.connector_url,
    action: 'mgr/item/create',
    fileUpload: true,
    fields: this.getFields(config),
    keys: [{
        key: Ext.EventObject.ENTER, shift: true, fn: function () {
          this.submit()
        }, scope: this
      }]
  });
  fileQueue.window.CreateItem.superclass.constructor.call(this, config);
};
Ext.extend(fileQueue.window.CreateItem, MODx.Window, {
  getFields: function (config) {
    return [{
        xtype: 'fileuploadfield'
        , fieldLabel: _('file')
        , buttonText: _('filequeue_window_select_file')
        , name: 'file'
        , id: config.id + '-file'
        , anchor: '100%'
      }];
  }

});
Ext.reg('filequeue-item-window-create', fileQueue.window.CreateItem);

fileQueue.window.viewLog = function (config) {
  config = config || {};
  if (!config.id) {
    config.id = 'filequeue-item-window-log';
  }
  Ext.applyIf(config, {
    title: _('filequeue_item_log'),
    width: 600,
    autoHeight: true,
//        url: fileQueue.config.connector_url,
//        action: 'mgr/item/get',
    fields: this.getFields(config),
    keys: [{
        key: Ext.EventObject.ENTER, shift: true, fn: function () {
          this.hide()
        }, scope: this
      }],
    buttons: [{
        text: _('filequeue_close')
        , scope: this
        , handler: function () {
          config.closeAction !== 'close' ? this.hide() : this.close();
        }
        , cls: 'primary-button'
      }]
  });
  fileQueue.window.CreateItem.superclass.constructor.call(this, config);
};
Ext.extend(fileQueue.window.viewLog, MODx.Window, {
  getFields: function (config) {
    return [{
        xtype: 'textarea'
        , name: 'log'
        , id: config.id + '-log'
        , anchor: '100%'
        , height: 400
        , readOnly: true
      }];
  }

});
Ext.reg('filequeue-item-window-log', fileQueue.window.viewLog);