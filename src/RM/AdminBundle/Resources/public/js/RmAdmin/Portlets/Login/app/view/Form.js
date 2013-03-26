Ext.define('Login.view.Form', {
    extend: 'Ext.form.Panel',
    alias: 'widget.login-form',

    frame:  true,
    border: false,

    title:'Login',

    width: 410,
    style: 'margin:0 auto;margin-top:10%; padding:10px',

    defaults:{
        xtype:'textfield'
    },

    items:[{
        name:'email',
        fieldLabel:'Email',
        width:386,
        vtype:'email',
        allowBlank:false
    },{
        name:'passwd',
        fieldLabel:'Password',
        width:385,
        vtype:'password',
        maxLength: 40,
        inputType: 'password',
        allowBlank:false
    }],

    buttons: [{
        text: 'Login',
        action: 'login'
    }]

});
