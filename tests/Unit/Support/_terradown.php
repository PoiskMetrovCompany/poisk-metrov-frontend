<?php

use Illuminate\Support\Facades\Artisan;

Artisan::call('db:wipe', ['--env' => 'testing']);
