<section class="news-list">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="sect-title">
                    <h3>{$locale.articles_events}</h3>
                </div>
            </div>
            <div class="col-md-12">
                <div class="news-list_wrapper clearfix">
                    {foreach from=$items item='item'}

                        <div class="col-md-4 col-sm-6 col-xs-12 masonry-item">
                            <div class="news-item">
                                <div class="news-item_img" style="background-image: url({if $item.image}/media/articles/{$item.id}_medium.jpg{else}/assets/img/placeholder_400.jpg{/if})">
                                    <div class="news-item_overlay">
                                        <div class="news-item_links">
                                            <a href="{$lang_suffix}/articles/{$item.sef}" class="link-icon"></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="news-item_main">
                                    <div class="news-item_title">
                                        <a href="{$lang_suffix}/articles/{$item.sef}">{$item.title}</a>
                                    </div>
                                    <div class="news-item_date">{$item.date|date_format:"%d.%m.%Y"}</div>
                                    <div class="news-item_descr">
                                        <p>{$item.brief}</p>
                                    </div>
                                    <div class="news-item_footer clearfix">
                                        <a href="{$lang_suffix}/articles/{$item.sef}" class="news-item_more">{$locale.read_more}<i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                        {*<div class="news-item_comments">2</div>*}
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>

            </div>
            {if $page_count>0}
                {include file="blocks/pagination.tpl" baseURL='articles'}
            {/if}
        </div>
    </div>
</section>



