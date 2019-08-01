//{namespace name="bundle/translation"}

Ext.define('Shopware.apps.SwagBundle.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.bundle-list-window',
    height: 450,
    title: '{s name=window_title}{/s}',

    configure: function () {
        return {
            listingGrid: 'Shopware.apps.SwagBundle.view.list.Bundle',
            listingStore: 'Shopware.apps.SwagBundle.store.Bundle'
        };
    }
});
