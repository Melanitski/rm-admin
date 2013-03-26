Ext.define('Panel.store.Menu', {
    extend: 'Ext.data.TreeStore',
    proxy: {
        type: 'ajax',
        url: '/admin/menu/list' //TODO move url to config
    },
    root: {},
    model: 'Panel.model.MenuItem',
    requires: 'Panel.model.MenuItem'
});
