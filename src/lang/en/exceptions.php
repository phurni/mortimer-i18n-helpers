<?php

/*
|--------------------------------------------------------------------------
| Exceptions
|--------------------------------------------------------------------------
|
*/

return [
    'Illuminate\Database\Eloquent\ModelNotFoundException.base' => "A required record is missing",
    'League\Csv\Exception.base' => "Processing failed while reading the CSV file (:message)",
    'League\Csv\UnavailableStream' => [
        '/`(.+?)`: failed to open stream/' => "The file `\\1` is not available.",
    ],
    'Exception.base' => "Processing stopped due to an unexpected error (:type :message)",
    'Exception' => [
        '/1062 Duplicate entry \'(.+?)\' for key/' => "Save failed because the field [\\1] has a duplicate",
        '/fgetcsv\(\): iconv stream filter/' => "The file content is unreadable"
    ],
];
