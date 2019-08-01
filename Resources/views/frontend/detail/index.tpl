{extends file="parent:frontend/detail/index.tpl"}

{block name="frontend_detail_index_detail"}
    {if $sArticle.attributes.swag_bundle && $sArticle.attributes.swag_bundle->get('has_bundle')}
        {$bundles = $sArticle.attributes.swag_bundle->get('bundles')}
        {include file="frontend/swag_bundle/detail_listing.tpl" bundles=$bundles}
    {/if}
    {$smarty.block.parent}
{/block}
