

      <!-- Begin page content -->
      <div class="container">

        <ul>
        <?php foreach ($tags['data']['records'] as $val) : ?>
            <li class="span4">
                <iframe class="vine-embed" src="<?php echo $val['shareUrl']; ?>/embed/postcard?autoplay=disabled" width="320" height="320" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>
            </li>
        <?php endforeach; ?>
        </ul>

      </div>


