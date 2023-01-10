<?php

if (! function_exists('trans_exception')) {
    /**
     * Translate the given exception.
     *
     * The translation mechanism is decomposed into multiple steps. All of the translations
     * are stored in the `exceptions.php` file in your `lang` subdirectory.
     *
     * An example content of the `exceptions.php` file:
     * ```
     * return [
     *     'Illuminate\Database\Eloquent\ModelNotFoundException.base' => "Un enregistrement requis est manquant dans la base de donnée",
     *     'League\Csv\UnavailableStream.base' => "Le fichier concerné n'est pas accessible (:message)",
     *     'League\Csv\UnavailableStream' => [
     *         '/`(.+?)`: failed to open stream/' => "Le fichier `\\1` n'est pas accessible.",
     *     ],
     *     'League\Csv\Exception.base' => "Erreur de traitement du fichier CSV (:message)",
     *     'Exception.base' => "Le traitement a été interrompu dû à une erreur imprévue (:type :message)",
     * ];
     * ```
     *
     * In order to give you control for generalizing translations, the mechanism will climb up the
     * inheritance chain of the passed `$exception` object. At each level, the mechanism tries these steps:
     *  - if a key matching the exception class exists:
     *    - iterate over the subarray trying to match the exception message with the key either as an
     *      exact string match or as a regex match. If matches, returns the value by replacing
     *      regex groups (using backslash notation) by their matches.
     *  - if a `base` subkey of the key matching the exception class exists:
     *    - return its value with :message and :type replacements.
     * if no matches found, the mechanism retries the same steps for the next ancestor until
     * reaching the base class `Exception`.
     *
     * @param  \Exception  $exception
     * @return string
     */
    function trans_exception($exception, array $replace = [], $locale = null)
    {
        try {
            $exceptionType = get_class($exception);
            $exceptionMessage = $exception->getMessage();
            $replace['message'] = $exceptionMessage;
            $replace['type'] = $exceptionType;

            do {
                // Look for translator keys as regex or simple string
                $scopedTranslations = \Lang::get("exceptions.$exceptionType");
                if (\Arr::isAssoc(\Arr::wrap($scopedTranslations))) {
                    foreach ($scopedTranslations as $regex => $message) {
                        $doesMatch = preg_match($regex, $exceptionMessage, $matches);
                        if ($doesMatch === false) {
                            // The translator key is not a regex, matches only if exact comparison
                            if ($regex === $exceptionMessage) {
                                return trans("exceptions.$exceptionType.$regex", $replace, $locale);
                            }
                        } else if ($doesMatch) {
                            // The translator key is a regex and matches so apply its replacements on the message
                            return strtr($message, collect($matches)->mapWithKeys(fn ($item, $index) => ["\\$index" => $item] )->toArray());
                        }
                    }
                }

                // None found, check for `base` translation matching exception type
                if (\Lang::has("exceptions.$exceptionType.base")) {
                    return trans("exceptions.$exceptionType.base", $replace, $locale);
                }

                // Still nothing found, climb up the ancestors chain
                $exceptionType = get_parent_class($exceptionType);
            } while ($exceptionType);

            // Nothing specific found, as last resort try the direct translation of the message
            return trans($exceptionMessage, $replace, $locale);
        } catch (\Exception $e) {
            // trans_exception() is not a good candidate for failure, so in case of failure
            // return the translated exception message (the argument, not the one of this catch clause)
            return trans($exception->getMessage(), $replace, $locale);
        }
    }
}
