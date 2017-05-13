fileQueue.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'filequeue-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('filequeue') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('filequeue_items'),
                layout: 'anchor',
                items: [{
                    xtype: 'filequeue-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    fileQueue.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(fileQueue.panel.Home, MODx.Panel);
Ext.reg('filequeue-panel-home', fileQueue.panel.Home);
