<?php

namespace Thettler\ExtendedLocalization\Tests\Fixtures;

use Thettler\ExtendedLocalization\Contracts\HasLanguages;
use Thettler\ExtendedLocalization\Contracts\Language;

enum WrongLanguage: string implements Language
{
    use HasLanguages;

    case Foo = 'se';
    case Bar = 'e3';
}
