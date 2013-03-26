Ext.define('Panel.controller.Menu', {

    extend:'Ext.app.Controller',
    view: ['Panel.view.Menu'],

    refs: [ {
        ref: 'panelMenu',
        selector: 'panel-menu'
    }, {
        ref: 'workspace',
        selector: 'panel-workspace'
    } ],

    init:function () {
        this.control( 'panel-menu', {
            afterrender: this.loadMenuItems,
            itemclick: this.itemClick
        });
    },

    loadMenuItems: function() {
        console.log('Menu loaded');
    },

    itemClick: function(viewItem, model) {
        var tab = Ext.create('Panel.view.component.Tab');
        tab.add(  Ext.create( 'Panel.view.Grid' ) );
        //TODO init real view grid panel
        this.getWorkspace().add( tab );
    }

});