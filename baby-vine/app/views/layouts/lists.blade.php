
<div id="content" >


    <?php foreach ($records as $val) : ?>

    <div id="block">
        <iframe class="vine-embed" src="<?php echo $val->shareUrl; ?>/embed/postcard" width="600" height="600" frameborder="0"></iframe>
        <script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>
    </div><!--/block-->

    <?php endforeach; ?>

<?php
/**
    <div id="box" style="clear-both;">

    </div><!--/box-->
    <ul id="pagination">
        <li class="next"><a href="/lists?page=<?php echo $page + 1; ?>"><?php echo $page+1;  ?></a></li>
    </ul>
 */
?>

    <div class="items clearfix">
    </div>


</div><!--/content-->



<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.esn.autobrowse.js"></script>
<script type="text/javascript" src="/js/jstorage.js"></script>
<?php
/**
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery-ias.min.js"></script>
<script type="text/javascript">
var ias = $.ias({
    container: "#content",
    item: "#box",
    pagination: "#pagination",
    next: ".next a",
    delay: 3000
});

ias.extension(new IASTriggerExtension({offset: 3}));
ias.extension(new IASSpinnerExtension());
ias.extension(new IASNoneLeftExtension({html: '<div style="text-align:center"><p><em>You reached the end!</em></p></div>'}));
</script>
 */
?>
<script type="text/javascript">
$(function () {
    $("#clear_cache").click( function (e) {
        e.preventDefault();
        $('#content .items').autobrowse('flush');
    })

        $("#content .items").autobrowse(
            {
                url: function (offset)
                    {
                        //return "http://api.flickr.com/services/feeds/photos_public.gne?tags=cat&tagmode=any&format=json&jsoncallback=?";
                        return "/api";
                    },
                        template: function (response)
                        {
                            console.log(response);
                            //console.log(response.length);
                            var markup='';
                            for (var i=0; i<response.length; i++)
                            {
                                var res = response[i];
                                console.log(response[i]);
                                console.log(res.sharetUrl);
                                markup += '<div id="block">'
                                        + '<iframe class="vine-embed" src="' + res.shareUrl+'/embed/postcard" width="600" height="600" frameborder="0"></iframe>'
                                        + '<script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"><\/script>'
                                        + '</div><!--/block-->'
                                        + '<div class="clearfix"></div>';
                            };
                            return markup;
                        },
                            //itemsReturned: function (response) { return response.items.length; },
                            offset: 0,
                            //max: 10,
                            loader: '<div class="loader"><span class="fa fa-spinner fa-spin"></span></div>',
                            useCache: false,
                            expiration: 10
            }
    );
});
</script>
