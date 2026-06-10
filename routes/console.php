<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:betamart', function () {
    $this->info('Beta Mart Warehouse siap digunakan.');
});
