Ext.define('Panel.view.component.Workspace', {
    extend:'Ext.tab.Panel',
    alias:'widget.panel-workspace',
    region:'center',

    flex: 1,
    layout: 'fit',
    enableTabScroll:true,

    items: [ /* {
            title: 'Editor tab',
            closable: false,
            autoScroll: true,
            margins: '0 0 0 0',
            items: [{
                xtype:'htmleditor',
                id:'bio2',
                fieldLabel:'Biography'
            }]
        }, {
            title: 'Grid tab',
            closable: false,
            autoScroll: true,
            margins: '0 0 0 0',
            items: [Ext.create('Panel.view.Grid')]
    }*/ ] //tabs content example

});

