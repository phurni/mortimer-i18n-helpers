<?php

if (! function_exists('trans_model')) {
    function array_matches($array, $count, ...$predicates)
    {
        return count($array) === $count && collect($predicates)->every(fn ($predicate, $index) => call_user_func($predicate, $array[$index]) );
    }

    function isModelInstance($object)
    {
        return is_a($object, 'Illuminate\Database\Eloquent\Model');
    }

    function isEnumInstance($object)
    {
        return $object instanceof \UnitEnum;
    }

    /**
     * Translate model names, attribute names or enum values.
     *
     */
    function trans_model($key, array $replace = [], $locale = null)
    {
        $key = \Arr::wrap($key);
        return match (true) {
            array_matches($key, 1, 'isEnumInstance')               => trans("models.enums.".get_class($key[0]).".{$key[0]->name}", $replace, $locale),
            array_matches($key, 1, 'isModelInstance')              => trans("models.names.".get_class($key[0]), $replace, $locale),
            array_matches($key, 2, 'isModelInstance', 'is_string') => trans("models.attributes.".get_class($key[0]).".{$key[1]}", $replace, $locale),
            array_matches($key, 2, 'is_string', 'is_string')       => trans("models.attributes.".get_class($key[0]).".{$key[1]}", $replace, $locale),
            array_matches($key, 2, 'isModelInstance')              => trans_choice("models.names.".get_class($key[0]), $key[1], $replace, $locale),
            array_matches($key, 2, 'is_string')                    => trans_choice("models.names.".get_class($key[0]), $key[1], $replace, $locale),
        };
    }

    /**
     * Alias for trans_model()
     *
     */
    function ___($key, array $replace = [], $locale = null)
    {
        return trans_model($key, $replace, $locale);
    }
}
