Ext.require([
    'Ext.*'
]);

Ext.application({
    name:'RmLoginLogin',
    controllers:['Main'],
    appFolder: '/bundles/rmadmin/js/RmAdmin/Portlets/Login/app',
    launch:function () {
        Ext.createByAlias('widget.login-panel',{
            renderTo:Ext.getBody(),
            width:300
        });
    }
});






