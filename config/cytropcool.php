<?php
return [
    'database' => [
        'table' => [
            'main_page_article' => 'article',
            'drinks' => 'drink',
            'user_holding' => 'inventory',
            'holdable' => 'holdable',
            'meta_holdable_type' => 'meta_holdable_type',
            'meta_holdable_category' => 'meta_holdable_category',
            'sessions' => 'session',
            'user_statistiques' => 'statistiques',

            'user' => 'user',
            'password_reset' => 'password_reset_tokens'
        ]
    ],
    'constant' => [
        'male' => 0.7,
        'female' => 0.6,
        'decay' => 0.20/3600,
        'time_before_decay' => 30*60,
        'time_to_max_eat' => 50*60,
        'time_to_max_no_eat' => 30*60,
        'time_to_max_bottoms_up' => 5*60,
        'time_to_max_no_bottoms_up' => 10*60
    ]

];


?>