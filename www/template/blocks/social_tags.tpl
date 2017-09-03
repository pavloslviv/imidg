<!--Facebook Open Graph, Google+ and Twitter Card Tags -->
<meta property="og:locale" content="{$social_data.locale_name}"/>
<meta property="og:site_name" content="{$social_data.site_name}"/>
<meta property="og:title" content="{$social_data.title}"/>
<meta property="og:url" content="{$HTTP_ROOT}{$lang_suffix}{$social_data.url}"/>
<meta property="og:type" content="{$social_data.type}"/>
<meta property="og:image" content="{$social_data.image}"/>
<meta property="og:description" content="{$social_data.description}"/>
{if $social_data.type=='product'}
    <meta property="product:price:amount" content="{$product.min_price|price}" />
    <meta property="product:price:currency" content="UAH" />
{/if}
{if $social_data.type=='article'}
    <meta property="article:published_time" content="{"c"|date:$page.date}"/>
{/if}
<meta itemprop="name" content="{$social_data.title}"/>
<meta itemprop="description" content="{$social_data.description}"/>
<meta itemprop="image" content="{$social_data.image}"/>

<meta name="twitter:title" content="{$social_data.title}"/>
<meta name="twitter:url" content="{$HTTP_ROOT}{$lang_suffix}{$social_data.url}"/>
<meta name="twitter:description" content="{$social_data.description}"/>
<meta name="twitter:image:src" content="{$social_data.image}"/>
<meta name="twitter:card" content="summary_large_image"/>
<!-- Facebook Open Graph, Google+ and Twitter Card Tags -->
