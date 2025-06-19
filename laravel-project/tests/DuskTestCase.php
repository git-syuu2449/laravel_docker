<?php

namespace Tests;

use Directory;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * スクリーンショットの保存ディレクトリ
     * @var string
     */
    protected $screenShotDirectory;
    
    /**
     * スクリーンショットのパスを返却
     * @param mixed $name
     * @param mixed $slot
     * @return string
     */
    protected function getScreenShotPath($name, $slot)
    {
        return $this->screenShotDirectory . $name . '-' . $slot;
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments([
            '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
            '--no-sandbox',
            '--disable-gpu',
            '--headless=new',
        ]);

        return RemoteWebDriver::create(
            'http://selenium:4444/wd/hub',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }


    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        try {
            $this->browse(function (Browser $browser) {
                $this->logoutWithPost($browser); // ログアウト
            });
        } catch (\Throwable $e) {
            // 念のため握りつぶす
        }

        parent::tearDown();
    }



    /**
     * ログアウトを強制的に行う
     * @param \Laravel\Dusk\Browser $browser
     * @return void
     */
    protected function logoutWithPost(Browser $browser, $target='/dashboard'): void
    {
        $browser->visit($target)
        ->script('
            const token = document.querySelector(\'meta[name="csrf-token"]\')?.content;
            if (!token) {
                console.error("CSRFトークンが取得できませんでした");
                return;
            }

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "/logout";

            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "_token";
            input.value = token;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        ');
    }
}
