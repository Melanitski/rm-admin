Ext.define('Login.view.Main', {

    extend: 'Ext.panel.Panel',
    alias: 'widget.login-panel',
    cls: 'login-form',
    controllers:['Login.controller.Main'],

    region: 'center',
    layout:'auto',
    border: false,

    items:[
        Ext.create('Login.view.Form')
    ]

});
