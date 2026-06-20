<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// `php artisan inspire`で短い引用文を表示するLaravel標準のサンプルコマンド。
Artisan::command('inspire', function () {
    // Console Commandの出力欄へ、ランダムに選ばれた引用文を書き出す。
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
