/**
 * @class Ext.app.Portal
 * @extends Object
 * A sample portal layout application class.
 */
Ext.define('RmAdmin.view.Viewport', {
    extend:'Ext.container.Viewport',

    initComponent:function () {
        Ext.apply(this, {
            id:'app-viewport',
            layout:{
                type:'border',
                padding:'0'
            },
            items:[
                new Ext.create('RmAdmin.view.Header'),
                new Ext.create('RmAdmin.view.Content'),
                new Ext.create('RmAdmin.view.Footer')
            ]
        });
        this.callParent(arguments);
    },

    getTools:function () {
        return [];
    }

});
