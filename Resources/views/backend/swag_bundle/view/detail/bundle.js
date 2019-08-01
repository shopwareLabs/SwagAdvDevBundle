//{namespace name="bundle/translation"}

Ext.define('Shopware.apps.SwagBundle.view.detail.Bundle', {
    extend: 'Shopware.model.Container',
    padding: 20,

    configure: function () {
        return {
            controller: 'SwagBundle',
            associations: [ 'products' ]
        };
    }
});
