Ext.require([
    'Ext.*'
]);

Ext.application( {
    name:'RmAdminPanel',
    controllers:['Main'],
    launch:function () {
        Ext.createByAlias('widget.panel-main', {
            renderTo:Ext.getBody(),
            width:300
        } );
    }
} );

