/**
 * @class Portal.view.Column
 * @extends Ext.container.Container
 * A layout column class used internally be {@link Portal.view.Columns}.
 */
Ext.define('RmAdmin.view.Header', {

    extend: 'Ext.panel.Header',
    alias: 'widget.app-header',
    region: 'north',
    layout: 'fit',

    title: 'Rademade admin panel'

});