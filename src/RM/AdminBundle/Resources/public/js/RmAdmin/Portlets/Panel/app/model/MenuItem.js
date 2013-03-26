Ext.define('Panel.model.MenuItem', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'id', type: 'string', mapping: 'id' },
        { name: 'text', type: 'string', mapping: 'text' },
        { name: 'leaf', type: 'boolean', mapping: 'leaf' }
    ]
});
