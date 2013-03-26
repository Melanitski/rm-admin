Ext.define('Panel.view.Main', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.panel-main',
    controllers:['Panel.controller.Main'],

    region: 'center',
    layout: 'border',
    border: false,

    items: [
        new Ext.create('Panel.view.component.Menu'),
        new Ext.create('Panel.view.component.Workspace')
    ]
});
