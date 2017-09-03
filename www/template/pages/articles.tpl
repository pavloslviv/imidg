<section class="post-details">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="sect-title">
                    <h3>{$locale.articles_events}</h3>
                </div>
            </div>
            <div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-12">
                <div class="article-wrapper">
                    {if $page.hide_image!=1}
                        <div class="article-img">
                            {if $page.image}
                                <img src="/media/articles/{$page.id}_medium.jpg" alt="{$page.title}">
                            {/if}
                        </div>
                    {/if}
                    <article>
                        <h2>{$page.title_full}</h2>
                        {if $page.text}{$page.text}{else}{$page.brief}{/if}
                    </article>
                    <div class="article-footer clearfix">
                        <div class="article-date">
                            {$locale.published}: <span>{$page.date|date_format:"%d.%m.%Y"}</span>
                        </div>
                        <!--
                        <div class="article-categories">
                            <span>Категорії: </span><a href="#">Події компанії</a>, <a href="#">Здоровя та діети</a>
                        </div>
                        <div class="article-comments"><span>2</span> Коментарі</div>
                        -->
                    </div>

                    {include file="blocks/social_panel.tpl"}

                    <!--<div class="post-share clearfix">
                        <div class="post-share_title">Поділіться новиною у соцмережах!</div>
                        <div class="social-menu">
                            <a href="#" class="twitter-icon">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="vk-icon">
                                <i class="fa fa-vk" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="facebook-icon">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="google-icon">
                                <i class="fa fa-google-plus" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="pinterest-icon">
                                <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>-->
                </div>
                <!--<div class="post-comments">
                    <div class="post-comments_title sect-title">
                        <h3>Коментарі</h3>
                    </div>
                    <div class="comments">
                        <div class="comments-item question clearfix">
                            <div class="comments-item_left">
                                <div class="comments-item_name">ВИКТОРИЯ, ХЕРСОН</div>
                                <div class="comments-item_date">01 Липень 2016</div>
                            </div>
                            <div class="comments-item_right">
                                <div class="comments-item_descr">
                                    <p>Понравился в магазине этот аромат, решила заказать на Мейк ап. Запах абсолютно идентичен, стойкость до 4-5 часов(это ведь туалетная вода), и на лето просто отличен! Мне очень нравится! Спасибо магазину и персоналу за качественный сервис)</p>
                                </div>
                            </div>
                        </div>
                        <div class="comments-item question clearfix">
                            <div class="comments-item_left">
                                <div class="comments-item_name">Олена, Львів</div>
                                <div class="comments-item_date">01 Липень 2016</div>
                            </div>
                            <div class="comments-item_right">
                                <div class="comments-item_descr">
                                    <p>Товар дуже якісний. Запах тримається довго. Навіть довше ніж очікувала) Дякую за швидку доставку.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-c add-comment">
                        <a href="#comment-popup" class="btn open-modal"><span>Залишити Коментар</span></a>
                    </div>
                </div>-->
            </div>
            <!--<div class="col-lg-3 col-lg-offset-1 col-md-4">
                <aside class="post-sidebar">
                    <div class="post-categories">
                        <div class="post-categories_title">Категорії</div>
                        <ul>
                            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Події компанії</a></li>
                            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Косметика для волосся</a></li>
                            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Декоративна косметика</a></li>
                            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Здоровя та діети</a></li>
                            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Категорія 1</a></li>
                            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Категорія 2</a></li>
                        </ul>
                    </div>
                    <div class="main-tabs post-tabs">
                        <div class="main-tabs_links">
                            <ul class="clearfix">
                                <li><a href="#" class="tab-link">Популярні</a></li>
                                <li><a href="#" class="tab-link">Останні</a></li>
                            </ul>
                        </div>
                        <div class="main-tabs_content">
                            <div class="one-tab popular-posts">
                                <div class="inner-post clearfix">
                                    <div class="inner-post_img">
                                        <a href="#"><img src="assets/img/inner-post_1.jpg" alt="alt"></a>
                                    </div>
                                    <div class="inner-post_text">
                                        <div class="inner-post_name">
                                            <a href="#">Імідж у Хмельницькому відкрито</a>
                                        </div>
                                        <div class="inner-post_date">13 Липень 2016</div>
                                    </div>
                                </div>
                                <div class="inner-post clearfix">
                                    <div class="inner-post_img">
                                        <a href="#"><img src="assets/img/inner-post_2.jpg" alt="alt"></a>
                                    </div>
                                    <div class="inner-post_text">
                                        <div class="inner-post_name">
                                            <a href="#">Вишиваний конкурс від Імідж</a>
                                        </div>
                                        <div class="inner-post_date">13 Липень 2016</div>
                                    </div>
                                </div>
                                <div class="inner-post clearfix">
                                    <div class="inner-post_img">
                                        <a href="#"><img src="assets/img/inner-post_3.jpg" alt="alt"></a>
                                    </div>
                                    <div class="inner-post_text">
                                        <div class="inner-post_name">
                                            <a href="#">День Поцілунків</a>
                                        </div>
                                        <div class="inner-post_date">13 Липень 2016</div>
                                    </div>
                                </div>
                            </div>
                            <div class="one-tab last-posts">
                                <div class="inner-post clearfix">
                                    <div class="inner-post_img">
                                        <a href="#"><img src="assets/img/inner-post_3.jpg" alt="alt"></a>
                                    </div>
                                    <div class="inner-post_text">
                                        <div class="inner-post_name">
                                            <a href="#">Імідж у Хмельницькому відкрито</a>
                                        </div>
                                        <div class="inner-post_date">13 Липень 2016</div>
                                    </div>
                                </div>
                                <div class="inner-post clearfix">
                                    <div class="inner-post_img">
                                        <a href="#"><img src="assets/img/inner-post_2.jpg" alt="alt"></a>
                                    </div>
                                    <div class="inner-post_text">
                                        <div class="inner-post_name">
                                            <a href="#">Вишиваний конкурс від Імідж</a>
                                        </div>
                                        <div class="inner-post_date">13 Липень 2016</div>
                                    </div>
                                </div>
                                <div class="inner-post clearfix">
                                    <div class="inner-post_img">
                                        <a href="#"><img src="assets/img/inner-post_1.jpg" alt="alt"></a>
                                    </div>
                                    <div class="inner-post_text">
                                        <div class="inner-post_name">
                                            <a href="#">День Поцілунків</a>
                                        </div>
                                        <div class="inner-post_date">13 Липень 2016</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="post-gallery">
                        <div class="post-gallery_title">Галерея</div>
                        <div class="post-gallery_wrapper clearfix">
                            <div class="post-gallery_item">
                                <a href="assets/img/post-gallery-b_1.jpg" rel="post-details_gallery" class="post-details_gallery" title="м. Львів, ТЦ “ВАМ” вул. Виговського, 100">
                                    <img src="assets/img/post-gallery-s_1.jpg" alt="alt">
                                    <span class="overlay"><span></span></span>
                                </a>
                            </div>
                            <div class="post-gallery_item">
                                <a href="assets/img/post-gallery-b_2.jpg" rel="post-details_gallery" class="post-details_gallery">
                                    <img src="assets/img/post-gallery-s_2.jpg" alt="alt">
                                    <span class="overlay"><span></span></span>
                                </a>
                            </div>
                            <div class="post-gallery_item">
                                <a href="assets/img/post-gallery-b_3.jpg" rel="post-details_gallery" class="post-details_gallery">
                                    <img src="assets/img/post-gallery-s_3.jpg" alt="alt">
                                    <span class="overlay"><span></span></span>
                                </a>
                            </div>
                            <div class="post-gallery_item">
                                <a href="assets/img/post-gallery-b_4.jpg" rel="post-details_gallery" class="post-details_gallery">
                                    <img src="assets/img/post-gallery-s_2.jpg" alt="alt">
                                    <span class="overlay"><span></span></span>
                                </a>
                            </div>
                            <div class="post-gallery_item">
                                <a href="assets/img/post-gallery-b_5.jpg" rel="post-details_gallery" class="post-details_gallery">
                                    <img src="assets/img/post-gallery-s_3.jpg" alt="alt">
                                    <span class="overlay"><span></span></span>
                                </a>
                            </div>
                            <div class="post-gallery_item">
                                <a href="assets/img/post-gallery-b_6.jpg" rel="post-details_gallery" class="post-details_gallery">
                                    <img src="assets/img/post-gallery-s_1.jpg" alt="alt">
                                    <span class="overlay"><span></span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>-->
        </div>
    </div>
</section>