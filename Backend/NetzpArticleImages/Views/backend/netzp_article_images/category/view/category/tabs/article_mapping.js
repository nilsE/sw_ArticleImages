//{block name="backend/category/view/tabs/article_mapping" append}

Ext.override(Shopware.apps.Category.view.category.tabs.ArticleMapping, {

    imageColumnRenderer: function (value, metaData, record) {

        var height = "{config name=netzparticleimages_height_categorylist}";

        return '<img src="/backend/NetzpArticleImages/getImage?articleid=' + 
                record.data.articleId + 
                '" style="height:' + height + 'px;">';
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
            columns.push({
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
