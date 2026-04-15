<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Each model gets its own salt so that User id=1 and Service id=1
    | produce completely different hash strings.
    |
    */

    'connections' => [

        'main' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-main',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'users' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-users',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'admins' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-admins',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'worker_profiles' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-worker_profiles',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'categories' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-categories',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'services' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-services',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'subscriptions' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-subscriptions',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'subscription_plans' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-subscription_plans',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'reviews' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-reviews',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'chats' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-chats',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'chat_messages' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-chat_messages',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'notifications' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-notifications',
            'length' => 12,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

        'admin_audit_logs' => [
            'salt' => env('HASHID_SALT', 'marketplace-default-salt-change-me') . '-admin_audit_logs',
            'length' => 10,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ],

    ],

];
