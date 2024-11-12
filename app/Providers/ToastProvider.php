<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Redirector;
use Inertia\ResponseFactory;

class ToastProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function boot()
    {
      View::macro('toast', function ($view, $message, $type = 'success') {
          return view($view, array_merge(['toast' => [
            'message' => $message,
            'type' => $type
          ]], $this->gatherData()));
          //return $this;
      });
      
      Redirector::macro('toast', function ($message, $type = 'info') {
        session()->flash('toast', [
            'message' => $message,
            'type' => $type,
        ]);

        return $this;
      });
      
      ResponseFactory::macro('toast', function ($message, $type = 'info') {
        session()->flash('toast', [
            'message' => $message,
            'type' => $type,
        ]);

        return $this;
      });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
