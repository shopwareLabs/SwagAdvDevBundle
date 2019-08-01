Ext.define('Shopware.apps.SwagBundle.store.Bundle', {
    extend: 'Shopware.store.Listing',

    configure: function () {
        return {
            controller: 'SwagBundle'
        };
    },

    model: 'Shopware.apps.SwagBundle.model.Bundle'
});
