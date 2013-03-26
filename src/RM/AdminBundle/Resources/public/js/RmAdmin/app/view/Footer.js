/**
 * @class Portal.view.Column
 * @extends Ext.container.Container
 * A layout column class used internally be {@link Portal.view.Columns}.
 */
Ext.define('RmAdmin.view.Footer', {

    extend: 'Ext.container.Container',
    alias: 'widget.app-footer',
    region: 'south',
    height: 20,
    layout: 'fit',

    html: 'Footer'

});