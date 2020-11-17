<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'signup',
        'solosignup',
        'DemoSignup',
        'signupnew',
        'SuperSecretAppLogin',
        'FileStore/CKEditor',
        'Chat/messagesApp',
        'App/LoginCheck',
        'API/*',
        'MailGun'
    ];
}
