//{namespace name="bundle/translation"}

Ext.define('Shopware.apps.SwagBundle.view.detail.Product', {
    extend: 'Shopware.grid.Association',
    alias: 'widget.bundle-view-detail-product',
    height: 300,
    title: '{s name="linked_products"}{/s}',

    configure: function() {
        return {
            controller: 'SwagBundle',
            columns: {
                name: {}
            }
        };
    }
});
