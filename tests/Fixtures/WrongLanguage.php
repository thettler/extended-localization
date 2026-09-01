<?php

namespace Thettler\ExtendedLocalization\Tests\Fixtures;

use Thettler\ExtendedLocalization\Contracts\HasLanguages;

enum WrongLanguage: string implements \Thettler\ExtendedLocalization\Contracts\Language
{
    use HasLanguages;

    case  Foo = 'se';
    case  Bar = 'e3';
}
