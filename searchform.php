<form class="search" role="search" action="<?=esc_url(SITE_URL)?>" method="get">
    <button type="submit" class="dashicons-search" title="Search" > </button>
    <input placeholder="ابحث في ميسان" type="search" name="s" title="Search" required value="<? the_search_query() ?>">
</form>