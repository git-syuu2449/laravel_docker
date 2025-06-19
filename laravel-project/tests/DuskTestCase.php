<?php

namespace Tests;

use Directory;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
        // まず親のtearDownを呼び出す（Duskはここでブラウザを閉じる処理などを行うはず）
        parent::tearDown(); 

        // ★追加: 各テスト後にブラウザのクッキーとローカルストレージをクリア
        $this->browse(function ($browser) { // tearDownではBrowserインスタンスを直接使えないので、browse()で取得
            try {
                // クッキーをクリア
                $browser->driver->manage()->deleteAllCookies();
                // ローカルストレージをクリア (JavaScriptを実行して削除)
                $browser->script('window.localStorage.clear();');
                // セッションストレージをクリア
                $browser->script('window.sessionStorage.clear();');
                Log::info('Browser cookies, local storage, and session storage cleared.');
            } catch (\Throwable $e) {
                // エラーが発生してもテストが中断しないように
                Log::error("Error clearing browser state: " . $e->getMessage());
            }
        });

        // Laravelアプリケーション側のセッションとキャッシュをクリア (以前の修正を維持)
        try {
            $sessionPath = storage_path('framework/sessions'); // あるいはハードコードパス
            if (is_dir($sessionPath)) {
                $items = glob($sessionPath . '/*');
                foreach ($items as $item) {
                    if (is_file($item)) {
                        unlink($item);
                    }
                }
            }
            Artisan::call('cache:clear');
            Log::info('Laravel session files and cache cleared.');
        } catch (\Throwable $e) {
            Log::error("Error clearing Laravel session/cache: " . $e->getMessage());
        }
    }
}
