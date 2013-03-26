Ext.define('Panel.view.Grid', {
    extend:'Ext.grid.Panel',
    alias: 'widget.panel-main-grid',
    controllers:['Panel.controller.Grid'],
    requires:[
//        'Ext.data.ArrayStore',
//        'Companies.model.Company'
    ],
    columnLines:true,
    columns:[{
        text:'User name',
        flex:1,
        sortable:true,
        dataIndex:'user_name'
    }, {
        text:'User email',
        flex:1,
        sortable:true,
        dataIndex:'user_email'
    }]
});