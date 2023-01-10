<?php

/*
|--------------------------------------------------------------------------
| Models class and attributes translations
|--------------------------------------------------------------------------
|
*/

return [
    'names' => [
        'App\Models\Exercise' => 'Questionnaire',
        'App\Models\Field' => 'Question',
    ],
    'attributes' => [
        'App\Models\Exercise' => [
            'title' => 'Name',
        ],
        'App\Models\Field' => [
            'label' => 'Caption',
            'value_kind' => 'Expected value',
        ],
    ],
    'enums' => [
        'App\Models\FieldValueKind' => [
            'single_line' => "Single line text",
            'single_line_list' => "List of simple items",
            'multi_line' => "Multi line text",
        ],
    ],
];
