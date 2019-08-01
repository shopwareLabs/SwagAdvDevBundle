<div class="bundle-container"
     style="border: 1px solid #c4c4c4; padding: 10px;
            margin: 5px 0;">

    {$products = $bundle->getLegacyProducts()}
    <div class="product-slider" data-product-slider="true">
        <div class="product-slider--container">
            {foreach $products as $product}
                <div class="product-slider--item">
                    {include file="frontend/listing/box_article.tpl" sArticle=$product productBoxLayout="slider"}
                </div>
            {/foreach}
        </div>
    </div>

    <a style="color:#fff !important;"
       class="btn is--primary"
       href="{url module=widgets controller=SwagBundle action=addBundle bundleId=$bundle->getId()}">
        Buy all products
    </a>
</div>