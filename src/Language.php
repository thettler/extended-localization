<?php

namespace Thettler\ExtendedLocalization;

use Thettler\ExtendedLocalization\Contracts\HasLanguages;

enum Language: string implements \Thettler\ExtendedLocalization\Contracts\Language
{
    use HasLanguages;

    case English = 'en';
    case German = 'de';
}
