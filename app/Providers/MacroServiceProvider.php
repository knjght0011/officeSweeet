<?php namespace App\Providers;

class MacroServiceProvider extends \Collective\Html\HtmlServiceProvider {

    /**
     * Register the form builder instance.
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function($app)
        {
            $form = new \App\Services\Macros($app['html'], $app['url'], $app['view'], $app['session.store']->token());

            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Register the html builder instance.
     *
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function($app)
        {
            return new \App\Services\Macros($app['html']);
        });
    }*/

}