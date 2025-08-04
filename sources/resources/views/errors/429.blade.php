@extends(
    'errors.template',
    [
        'title' => 'Поиск метров - Too Many Requests',
        'code' => 429,
        'reason' => 'Too Many Requests'
    ]
)