<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{

    protected $screenShotDirectory = "example/";

    /**
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }

    public function testCookieDomainCheck(): void
    {
        $this->browse(function (Browser $browser) {
                dump($browser->driver->manage()->getCookies()); // debug
                $cookie = $browser->cookie('laravel_session');
                dump($cookie);
            $browser
                    ->visit('http://nginx/debug-domain')
                    // ->visit('/debug-domain')
                    ->pause(1000)
                    ->screenshot($this->getScreenShotPath('cookie-domain-check', 1));
        });
    }


    public function testSunctumDomainCheck(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://nginx/debug-sanctum-domains'); // http://nginx は環境に合わせてください

            // ページが完全にロードされるのを待つ (重要)
            $browser->pause(1000); // 1秒待機 (または waitForText など)

            // ページの生のソースコードをダンプ
            $pageSource = $browser->driver->getPageSource();
            dump($pageSource);

            // 必要であれば、JSONとしてパースし、'nginx'が含まれているか確認
            // ただし、生のJSON文字列がページソースとして取得されるため、
            // parse_urlで得られるホスト名が配列に含まれているか確認する方が確実です
            $jsonResponse = json_decode(strip_tags($pageSource), true); // HTMLタグを除去してからJSONパース
            dump($jsonResponse);

            // Nginxドメインが配列に含まれていることをアサート
            $this->assertContains('nginx', $jsonResponse);

            $browser->screenshot($this->getScreenShotPath('sanctum-domain-check', 1));
        });
    }


    public function testDebugAppUrl(): void
{
    $this->browse(function (Browser $browser) {
        $browser->visit('http://nginx/debug-app-url')
                ->pause(500);
        dump(json_decode(strip_tags($browser->driver->getPageSource()), true));
    });
}
}
