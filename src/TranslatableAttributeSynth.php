<?php

namespace Thettler\ExtendedLocalization;

use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class TranslatableAttributeSynth extends Synth
{
    public static $key = 'translatable-attribute';

    public static function match($target)
    {
        return $target instanceof TranslatableAttribute;
    }

    public function dehydrate($target)
    {
        return [$target->getTranslations(), []];
    }

    public function hydrate($value)
    {
        return TranslatableAttribute::new($value);
    }

    public function get(&$target, $key)
    {
        return $target->getTranslation($key);
    }

    public function set(&$target, $key, $value)
    {
        $target->setTranslation($key, $value);
    }
}
