var fileQueue = function (config) {
    config = config || {};
    fileQueue.superclass.constructor.call(this, config);
};
Ext.extend(fileQueue, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('filequeue', fileQueue);

fileQueue = new fileQueue();