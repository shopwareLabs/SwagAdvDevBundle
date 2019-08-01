Ext.define('Shopware.apps.SwagBundle.model.Bundle', {
    extend: 'Shopware.data.Model',

    configure: function () {
        return {
            controller: 'SwagBundle',
            detail: 'Shopware.apps.SwagBundle.view.detail.Bundle'
        };
    },

    fields: [
        { name: 'id', type: 'int', useNull: true },
        { name: 'name', type: 'string' },
        { name: 'active', type: 'boolean' }
    ],

    associations: [
        {
            relation: 'ManyToMany',
            type: 'hasMany',
            model: 'Shopware.apps.SwagBundle.model.Product',
            name: 'getProducts',
            associationKey: 'products'
        }
    ]
});
