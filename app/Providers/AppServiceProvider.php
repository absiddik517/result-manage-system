<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

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
        $this->cacheEssentials();
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        //\Illuminate\Http\Resources\Json\JsonResource::withoutWrapping();
    }
    
    private function cacheEssentials(){
      if (!Cache::has('institute') || !Cache::has('logo') || !Cache::has('admit_template')) {
        $settings = \DB::table('settings')->select('key', 'value')->get();
        $institute = json_decode($settings->where('key', 'institute')->first()->value, true);
        Cache::put('institute', $institute, now()->addMinutes(60*24*365));
        Cache::put('logo', [
          "asset" => asset('storage/'.$settings->where('key', 'logo')->first()->value),
          "real_path" => base_path('public/storage/'.$settings->where('key', 'logo')->first()->value),
        ], now()->addMinutes(60*24*365));
        Cache::put('admit_template', $settings->where('key', 'admit_template')->first()->value, now()->addMinutes(60*24*365));
      }
      
      config(['pdf.watermark_image_path' => Cache::get('logo')['real_path']]);
    }
}
