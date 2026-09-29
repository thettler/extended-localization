<?php

namespace Thettler\ExtendedLocalization\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Thettler\ExtendedLocalization\Contracts\HasTranslatableAttributes;
use Thettler\ExtendedLocalization\Contracts\Translatable;
use Thettler\ExtendedLocalization\TranslatableAttribute;

class TestModel extends Model implements Translatable
{
    use HasTranslatableAttributes;

    protected $table = 'test_models';

    protected $guarded = [];

    protected $casts = [
        'text' => TranslatableAttribute::class,
        'text_nullable' => TranslatableAttribute::class.':nullable',
        'not_translatable' => 'bool',
    ];
}
