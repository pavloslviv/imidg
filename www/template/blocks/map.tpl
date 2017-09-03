


<section class="gallery-carousel">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="sect-title">
                    <h3>{$locale.gallery}</h3>
                </div>
            </div>
            <div class="col-md-12">
                <div class="gallery-carousel_wrapper">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="map-area">
    <div id="map_canvas"></div>
</section>

<script type="text/template" id="tmpl_slider_item">
    <% items.forEach(function(item) { %>
        <div class="gallery-carousel_item">
            <a href="<%= item.image %>" rel="gallery-item" class="gallery-item" title="<%- item.city %>, <%- item.address %>">
                <% if(item.image){ %>
                    <img src="<%= item.image %>" alt="<%- item.city %>, <%- item.address %>">
                <% } else { %>
                    <img src="/template/images/placeholder_140.png" alt="<%- item.city %>, <%- item.address %>">
                <% } %>
                <span class="gallery-carousel_text">
                    <span class="gallery-carousel_address">
                        <i class="icon-placeholder-1"></i><%- item.city %>, <%- item.address %>
                    </span>
                    <% if(item.phone){ %>
                        <span class="gallery-carousel_tel">
                            <i class="icon-phone-call-2"></i><%- item.phone %>
                        </span>
                    <% } %>
                </span>
            </a>
        </div>
    <% }); %>
</script>


<script type="text/html" id="tmpl_store_item">
    <li data-index="<%= index %>" id="map_slide_<%= index %>">
        <% if(item.image){ %>
        <a rel="store_gallery" title="<%- item.city %>, <%- item.address %>" href="<%= item.image %>" class="thumbnail" style="background-image: url(<%= item.image %>);"></a>
        <% } else { %>
        <div class="thumbnail" style="background-image: url(/template/images/placeholder_140.png);"></div>
        <% } %>
        <div class="info">
            <div class="address">
                <i class="icon icon-map-marker"></i>
                <%- item.city %>,<br>
                <%- item.address %>
            </div>
            <% if(item.phone){ %>
            <div class="phone">
                <i class="icon icon-phone-small"></i>
                <%- item.phone %>
            </div>
            <% } %>
        </div>
    </li>
</script>
{*<script type="text/javascript" src="/lib/js/fancybox/jquery.fancybox.pack.js"></script>*}
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCPhA5vir_BSPURlaca0n-Ouprh-8ipyrk&sensor=false&lang=uk"></script>

<script type="text/javascript">
    var shopMap = {$map.value};
</script>
