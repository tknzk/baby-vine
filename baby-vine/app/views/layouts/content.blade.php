
<div id="content">

    <?php foreach ($records as $val) : ?>

    <div id="block">
        <iframe class="vine-embed" src="<?php echo $val->shareUrl; ?>/embed/postcard" width="320" height="320" frameborder="0"></iframe>
        <script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>
    </div><!--/block-->

    <?php endforeach; ?>

</div><!--/content-->

