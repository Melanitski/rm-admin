Ext.define('Panel.view.component.Menu', {
    extend:'Ext.tree.Panel',
    alias:'widget.panel-menu',
    controllers:['Panel.controller.Menu'],
    store: Ext.create('Panel.store.Menu'),

    region:'west',
    resizable: true,
    animate:true,
    containerScroll: true,
    autoScroll: true,
    rootVisible: false,

    width: 175,
    minSize: 100,
    maxSize: 250,

    title:'Menu',
    items: [],

    initComponent: function() {
        //TODO menu item loads
        this.callParent(arguments);
    }

} );