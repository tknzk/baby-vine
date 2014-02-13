<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="vine,babyvine,babyphoto,baby" />
<meta name="description" content=" Babyvine is only cute and funny babies animation gallery." />
<title>babyvine</title>
<?php
/**
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="/js/jquery.autopager-1.0.0.js"></script>
<script src="/js/jquery-ias.min.js"></script>
*/
?>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Norican' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="/img/favicon.ico" />
<style>
    #header h1 {
        font-family: 'Norican', cursive;
        font-size: 48px;
        font-weight: 400;
        width: 200px;
        display: block;
        margin: 0px;
        color: #ADEAE5;
        float: left;
        padding-top: 10px;
        padding-right: 0px;
        padding-bottom: 0px;
        padding-left: 0px;
    }

    #header h2 {
        color: #666;
        display: block;
        padding-top: 20px;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-weight: normal;
        margin: 0px;
        float: left;
    }
</style>
<?php
/*
<script type="text/javascript">
$(function() {
    $.autopager();
});
</script>
 */
?>

</head>

<body>

<?php
/**
<script type="text/javascript">
var ias = $.ias({
    container: ".posts",
        item: ".block",
        pagination: ".pager",
        next: ".next"
});

ias.extension(new IASTriggerExtension({offset: 2}));
ias.extension(new IASSpinnerExtension());
ias.extension(new IASNoneLeftExtension({html: '<div style="text-align:center"><p><em>You reached the end!</em></p></div>'}));
</script>
 */
?>


    @yield('main')

</body>
</html>
