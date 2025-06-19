<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Models\User;
use App\Enums\Role;


/**
 * ログインテスト
 * 前提：seed実行直後であること
 */
class LoginTest extends DuskTestCase
{
    protected $screenShotDirectory = "login/";

    /**
     * ログイン画面を表示
     */
    public function testLoginVisit(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login')
                    ->screenshot($this->getScreenShotPath('login_visit', 1))
                    ->assertSee('Email');
        });
    }

    /**
     * ログインエラー
     * php artisan dusk --filter testLoginError
     */
    public function testLoginError(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://nginx/login')
                ->pause(1500); // ページロードを待つ

            // CSRFトークン隠しフィールドの存在を確認
            $browser->assertPresent('input[name="_token"]');

            // CSRFトークンの値をダンプして確認
            $csrfToken = $browser->value('input[name="_token"]');
            dump("CSRF Token in form: " . $csrfToken);

            dump("Before type: ", $browser->driver->manage()->getCookies());
            // ... (既存の type() 以降のコード) ...
            $browser->type('#email', 'error@error.com')
                ->type('#password', 'error')
                ->screenshot($this->getScreenShotPath('login_error', 1));

            dump("After type, before press: ", $browser->driver->manage()->getCookies());
            // ... (既存の press() 以降のコード) ...
            $browser->press('@login')
                ->waitFor('@login');

            // dump("After press - Current Path: " . $browser->path());
            dump("After press - Page Source: " . $browser->driver->getPageSource());
            dump("After press: ", $browser->driver->manage()->getCookies());

            $browser->assertRouteIs('login')
                ->screenshot($this->getScreenShotPath('login_error', 2));
        });
    }

    /**
     * ログイン成功:管理者
     */
    public function testLoginSuccessAdmin(): void
    {
        $user = User::where('role', Role::Admin)->first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visitRoute('login')
                ->waitFor('#email')
                ->type('#email', 'test1@email.com')
                ->waitFor('#password')
                ->type('#password', 'password')
                ->screenshot($this->getScreenShotPath('login_success_admin', 1))
                ->press('@login')
                ->waitForRoute('admin.dashboard') // パスが表示されるまで待つ
                ->assertRouteIs('admin.dashboard') // 管理ユーザー初期画面
                ->screenshot($this->getScreenShotPath('login_success_admin', 2));
            
            $browser->logout();
        });
    }

    /**
     * ログイン成功:一般ユーザー
     */
    public function testLoginSuccessUser(): void
    {
        $user = User::where('role',Role::User)->first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visitRoute('login')
                ->pause(15000)
                ->waitFor('#email')
                ->type('#email', 'test2@email.com')
                ->waitFor('#password')
                ->type('#password', 'password')
                ->screenshot($this->getScreenShotPath('login_success_user', 1))
                ->press('@login')
                ->waitForRoute('dashboard')// パスが表示されるまで待つ
                ->assertRouteIs('dashboard')// 一般ユーザー初期画面
                ->screenshot($this->getScreenShotPath('login_success_user', 2));
            $browser->logout();
        });
    }
}
