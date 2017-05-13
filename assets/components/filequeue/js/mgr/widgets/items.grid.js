fileQueue.grid.Items = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'filequeue-grid-items';
    }
    Ext.applyIf(config, {
        url: fileQueue.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/item/getlist'
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
//            getRowClass: function (rec) {
//                return !rec.data.active
//                    ? 'filequeue-grid-row-disabled'
//                    : '';
//            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    });
    fileQueue.grid.Items.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(fileQueue.grid.Items, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = fileQueue.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    createItem: function (btn, e) {
        var w = MODx.load({
            xtype: 'filequeue-item-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.show(e.target);
    },
    
    viewLog: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/item/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'filequeue-item-window-log',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    removeItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('filequeue_items_remove')
                : _('filequeue_item_remove'),
            text: ids.length > 1
                ? _('filequeue_items_remove_confirm')
                : _('filequeue_item_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/item/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },

    getFields: function () {
        return ['id', 'name', 'createdon', 'createdby', 'status', 'processedon', 'actions', 'username'];
    },

    getColumns: function () {
        return [{
            header: _('filequeue_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 30
        }, {
            header: _('filequeue_item_name'),
            dataIndex: 'name',
            sortable: true,
        }, {
            header: _('filequeue_item_createdon'),
            dataIndex: 'createdon',
            sortable: true,
            renderer: fileQueue.utils.renderDatetime,
        }, {
            header: _('filequeue_item_createdby'),
            dataIndex: 'username',
            renderer: function(val, cell, row) {
                return fileQueue.utils.renderUser(val, row.data['createdby'], true);
            },
            sortable: true,
        }, {
            header: _('filequeue_item_status'),
            dataIndex: 'status',
            renderer: fileQueue.utils.renderStatus,
            sortable: true,
        }, {
            header: _('filequeue_item_processedon'),
            dataIndex: 'processedon',
            renderer: fileQueue.utils.renderDatetime,
            sortable: true,
        }, {
            header: _('filequeue_grid_actions'),
            dataIndex: 'actions',
            renderer: fileQueue.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('filequeue_item_create'),
            handler: this.createItem,
            scope: this
        }];
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },

});
Ext.reg('filequeue-grid-items', fileQueue.grid.Items);
