<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
        //     return preg_match('/^(09|01[2|6|8|9])+([0-9]{8})\b|\b(07[0|6|7|8|9]|03[2|3|4|5|6|7|8|9]|08[1|2|3|4|5|6|8|9])+([0-9]{7})\b|\b(02[4|8])+([0-9]{8})\b|\b(021[0|1|2|3|4|5|6|8|9]|022[0|1|2|5|6|7|8|9]|023[2-9]|025[1|2|4|5|6|7|8|9]|026[0|1|2|3|9]|027[0-7]|029[0|1|2|3|4|6|7|9])+([0-9]{7})\b/', $value) && strlen($value) >= 6 && strlen($value) <= 11; //
        // });

        // Validator::replacer('phone', function ($message, $attribute, $rule, $parameters) {
        //     return str_replace(':attribute', $attribute, ':attribute is invalid phone number');
        // });
    }
}
