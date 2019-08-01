{extends file="parent:frontend/listing/product-box/product-badges.tpl"}

{block name="frontend_listing_box_article_new"}
    {$smarty.block.parent}
    {if $sArticle.attributes.swag_bundle && $sArticle.attributes.swag_bundle->get('has_bundle')}
        {include file="frontend/swag_bundle/listing_badge.tpl"}
    {/if}
{/block}
