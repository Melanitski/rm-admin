Ext.define('Login.controller.Main', {

    extend:'Ext.app.Controller',
    view: ['Login.view.Main'],

    refs:[{
        ref:'loginForm',
        selector:'widget.login-form'
    }],

    init:function () {
        this.control({
            'login-form button[action=login]': {
                click: this.login
            }
        });
    },

    login: function() {
        console.log('Login pressed');
    }

});