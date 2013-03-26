Ext.Loader.setPath('Panel',  '/bundles/rmadmin/js/RmAdmin/Portlets/Panel/app');
Ext.Loader.setPath('Login',  '/bundles/rmadmin/js/RmAdmin/Portlets/Login/app');
Ext.Loader.setPath('Ext.ux', '/bundles/rmadmin/js/RmAdmin/ux');

Ext.require([
    'Ext.*'
]);

Ext.application({
    name:'RmAdmin',
    autoCreateViewport:true,
    appFolder: '/bundles/rmadmin/js/RmAdmin/app',
    controllers:['MVCLoader', 'Main'],
    launch: function() {
        Ext.get('page-loader').hide();
    }
});






