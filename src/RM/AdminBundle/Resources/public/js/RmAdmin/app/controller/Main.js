Ext.define('RmAdmin.controller.Main', {

    extend:'Ext.app.Controller',

    refs: [ {
        ref: 'contentView',
        selector: 'app-content'
    } ],

    init:function () {

    },

    onLaunch: function() {
        if (!true) {//TODO login check
            this.getContentView().add( Ext.create('Login.view.Main') );
            //TODO bind login listener
        } else {
            this.getContentView().add( Ext.create('Panel.view.Main') );
        }
    }

});
