fileQueue.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'filequeue-panel-home',
            renderTo: 'filequeue-panel-home-div'
        }]
    });
    fileQueue.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(fileQueue.page.Home, MODx.Component);
Ext.reg('filequeue-page-home', fileQueue.page.Home);