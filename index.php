<?php


$user = 'tkm.knzk@gmail.com';
$pass = 'vine1123';

$session = login($user, $pass);

//$me = call($session, 'users/me');
//var_dump($me);

$tag = "baby";
$tags = call($session, 'timelines/tags/'. urlencode($tag));
//var_dump($tags);

$logout = logout($session);
//var_dump($logout);

function call($session, $api)
{
    $header = array(
        'vine-session-id:'.$session->data->key,
    );

    $api_url       = 'https://api.vineapp.com/' . $api;

    $curl = curl_init();
    curl_setopt($curl,  CURLOPT_URL,            $api_url);
    curl_setopt($curl,  CURLOPT_HTTPHEADER,     $header);
    curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);

    $response   = curl_exec($curl);
    $result     = json_decode($response, true);
    return $result;
}

function login($user, $pass)
{

    $auth_url       = 'https://api.vineapp.com/users/authenticate';
    $login_values   = array(
        'username' => $user,
        'password' => $pass,
    );

    $curl = curl_init();
    curl_setopt($curl,  CURLOPT_URL,            $auth_url);
    curl_setopt($curl,  CURLOPT_POST,           true);
    curl_setopt($curl,  CURLOPT_HEADER,         false);
    curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,  CURLOPT_POSTFIELDS,     $login_values);

    $response = curl_exec($curl);

    $session = json_decode($response);

    return $session;
}

function logout($session)
{
    $header = array(
        'vine-session-id:'.$session->data->key,
    );

    $auth_url       = 'https://api.vineapp.com/users/authenticate';

    $curl = curl_init();
    curl_setopt($curl,  CURLOPT_URL,            $auth_url);
    curl_setopt($curl,  CURLOPT_CUSTOMREQUEST,  'DELETE');
    curl_setopt($curl,  CURLOPT_HTTPHEADER,     $header);
    curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    $session = json_decode($response);

    return $session;
}
?>
<html>
<body>
<?php foreach ($tags['data']['records'] as $tag) : ?>
<iframe class="vine-embed" src="<?php echo $tag['shareUrl']; ?>/embed/simple" width="600" height="600" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>
<br />
<?php endforeach ; ?>
</body>
</html>
