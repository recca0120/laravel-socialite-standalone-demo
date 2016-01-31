<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Laravel\Socialite\SocialiteManager;
use Symfony\Component\HttpFoundation\Session\Session;

/*
 * 用   \Illuminate\Support\Fluent
 * 替代 \Illuminate\Container\Container
 */
$app = new Fluent();

/*
 * 用   \Illuminate\Support\Fluent
 * 替代 \Illuminate\Contracts\Config\Repository
 */
$config = new Fluent();

/*
 * 取得 Request
 */
$request = Request::capture();

/*
 * 使用 Symfony Session
 */
$session = new Session();

/*
 * session start
 */
$session->start();

/*
 * 設置 session
 */
$request->setSession($session);

/*
 * Facebook飍數
 */
$config['services.facebook'] = [
    'client_id'     => '900358040081723',
    'client_secret' => '06134ad6946069c6457d06688b22c259',
    'redirect'      => 'http://localhost/test/socialite/index.php/callback',
];

/*
 * 將 config, request置入 app
 */
$app['config'] = $config;
$app['request'] = $request;

// 以上為 socalite app所需設定，接下來參考官方文件即可

$socialiteManager = new SocialiteManager($app);
$provider = $socialiteManager
    ->with('facebook')
    /*
     * 只要是oauth2就必須執行此方法，
     * 因為我們用的是Symfony Session而不是Laravel Session
     */
    ->stateless();

if (strpos($_SERVER['REQUEST_URI'], '/callback') === false) {
    $response = $provider->redirect();

    return $response->send();
}

echo '<pre>';
var_dump((array) $provider->user());
