//{block name="backend/order/view/detail/position" append}

Ext.override(Shopware.apps.Order.view.detail.Position, {

    imageColumnRenderer: function (value, metaData, record) {
        var height = "{config name=netzparticleimages_height_articlelist}";

        return '<img src="/backend/NetzpArticleImages/getImage?articleid=' 
                + encodeURIComponent(record.data.articleId) + 
                '" style="height: ' + height + 'px;">';
    },

    getColumns: function() {

        var me = this,
            columns = me.callParent(arguments);
        // check if column for description is already existing
        var existing = false;
        for(var i = 0; i < columns.length; i++) {
            if(columns[i].dataIndex == 'imgSrc') {
                existing = true;
                break;
            }
        }

        if (!existing) {
            columns.splice(columns.length - 1, 0, {
                dataIndex: 'imgSrc',
                header: 'Bild',
                width: 150,
                renderer: me.imageColumnRenderer                
            });
        }

        return columns;
    }
});
//{/block}

//{block name="backend/order/view/list/position" append}

Ext.override(Shopware.apps.Order.view.list.Position, {

    imageColumnRenderer: function (value, metaData, record) {
        var height = "{config name=netzparticleimages_height_articlelist}";

        return '<img src="/backend/NetzpArticleImages/getImage?articleid=' 
                + encodeURIComponent(record.data.articleId) + 
                '" style="height: ' + height + 'px;">';
    },

    getColumns: function() {

        var me = this,
            columns = me.callParent(arguments);
        // check if column for description is already existing
        var existing = false;
        for(var i = 0; i < columns.length; i++) {
            if(columns[i].dataIndex == 'imgSrc') {
                existing = true;
                break;
            }
        }

        if (!existing) {
            columns.splice(columns.length - 1, 0, {
                dataIndex: 'imgSrc',
                header: 'Bild',
                width: 150,
                renderer: me.imageColumnRenderer                
            });
        }

        return columns;
    }
});
//{/block}

