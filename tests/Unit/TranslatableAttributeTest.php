<?php

declare(strict_types=1);

use Thettler\ExtendedLocalization\Exceptions\LanguageNotDefinedException;
use Thettler\ExtendedLocalization\Language;
use Thettler\ExtendedLocalization\Tests\Fixtures\WrongLanguage;
use Thettler\ExtendedLocalization\TranslatableAttribute;

it('can create TranslatableAttribute', function () {
    app()->setLocale('de');

    $translatableAttribute = new TranslatableAttribute(Language::class, [
        Language::German->value => 'Deutsch',
        Language::English->value => 'English',
    ]);

    expect($translatableAttribute->getTranslation(Language::German))->toBe('Deutsch');
    expect($translatableAttribute->getTranslation('de'))->toBe('Deutsch');
    expect($translatableAttribute->getTranslation(Language::English))->toBe('English');
    expect($translatableAttribute->getTranslation('en'))->toBe('English');
    expect($translatableAttribute->getTranslation())->toBe('Deutsch');

    $translatableAttribute = new TranslatableAttribute(Language::class);

    $translatableAttribute->setTranslation('de', 'Deutsch_2');
    $translatableAttribute->setTranslation('en', 'English_2');

    expect($translatableAttribute->getTranslation('de'))->toBe('Deutsch_2');
    expect($translatableAttribute->getTranslation(Language::German))->toBe('Deutsch_2');
    expect($translatableAttribute->getTranslation('en'))->toBe('English_2');
    expect($translatableAttribute->getTranslation(Language::English))->toBe('English_2');
    expect($translatableAttribute->getTranslation())->toBe('Deutsch_2');

    $translatableAttribute->setTranslation(Language::German, 'Deutsch_3');
    $translatableAttribute->setTranslation(Language::English, 'English_3');

    expect($translatableAttribute->getTranslation(Language::German))->toBe('Deutsch_3');
    expect($translatableAttribute->getTranslation(Language::English))->toBe('English_3');
});

it('cannot create TranslatableAttribute with not defined Language', function () {
    new TranslatableAttribute(Language::class, [
        'does_not_exist' => 'Deutsch',
    ]);
})->throws(
    LanguageNotDefinedException::class,
    'Language "does_not_exist" does not exist in Language Enum. Available languages: en, de'
);

it('cannot use different Language Enum for TranslatableAttribute', function () {
    new TranslatableAttribute(Language::class, [
        'de' => 'Deutsch',
    ])->getTranslation(WrongLanguage::Foo);
})->throws(
    InvalidArgumentException::class,
    'The given language (Thettler\ExtendedLocalization\Tests\Fixtures\WrongLanguage) does not match the language in the TranslatableAttribute (Thettler\ExtendedLocalization\Language).'
);

it('can be used with Array access', function () {
    app()->setLocale('de');

    $translatableAttribute = new TranslatableAttribute(Language::class, [
        'de' => 'Deutsch',
        'en' => 'English',
    ]);

    expect($translatableAttribute['de'])->toBe('Deutsch');
    expect($translatableAttribute[Language::German])->toBe('Deutsch');
    expect($translatableAttribute['en'])->toBe('English');
    expect($translatableAttribute[Language::English])->toBe('English');

    $translatableAttribute['de'] = 'Deutsch_2';
    $translatableAttribute['en'] = 'English_2';

    expect($translatableAttribute['de'])->toBe('Deutsch_2');
    expect($translatableAttribute['en'])->toBe('English_2');

    unset($translatableAttribute['de']);
    expect($translatableAttribute->doesTranslationExist('de'))->toBeFalse();
    unset($translatableAttribute[Language::English]);
    expect($translatableAttribute->doesTranslationExist('en'))->toBeFalse();
});

it('can be used with object access', function () {
    app()->setLocale('de');

    $translatableAttribute = new TranslatableAttribute(Language::class, [
        'de' => 'Deutsch',
        'en' => 'English',
    ]);

    expect($translatableAttribute->de)->toBe('Deutsch');
    expect($translatableAttribute->en)->toBe('English');
    expect($translatableAttribute())->toBe('Deutsch');

    $translatableAttribute->de = 'Deutsch_2';
    $translatableAttribute->en = 'English_2';

    expect($translatableAttribute->de)->toBe('Deutsch_2');
    expect($translatableAttribute->en)->toBe('English_2');

    unset($translatableAttribute->de);
    expect($translatableAttribute->doesTranslationExist('de'))->toBeFalse();
});

it('can be stringified', function () {
    app()->setLocale('de');

    $translatableAttribute = new TranslatableAttribute(Language::class, [
        'de' => 'Deutsch',
        'en' => 'English',
    ]);

    expect((string) $translatableAttribute)->toBe('Deutsch');
    app()->setLocale('en');
    expect((string) $translatableAttribute)->toBe('English');
});

it('can be transformed to Json', function () {
    $value = [
        'de' => 'Deutsch',
        'en' => 'English',
    ];

    $translatableAttribute = new TranslatableAttribute(Language::class, $value);

    expect($translatableAttribute->toJson())->toBe(json_encode($value));
});

it('can check if Translation exists', function () {
    $value = [
        'en' => 'English',
    ];

    $translatableAttribute = new TranslatableAttribute(Language::class, $value);

    expect($translatableAttribute->doesTranslationExist(Language::German))->toBeFalse();
    expect($translatableAttribute->doesTranslationExist(Language::English))->toBeTrue();
});

it('can check if Translation is Empty', function ($value, $expected) {
    $translatableAttribute = new TranslatableAttribute(Language::class, [
        'de' => $value,
    ]);

    expect($translatableAttribute->isTranslationEmpty(Language::German))->toBe($expected);
    expect($translatableAttribute->isTranslationEmpty(Language::English))->toBeTrue();
})->with([['', true], ['Deutsch', false], [null, true]]);

it('can be iterated', function () {
    $translatableAttribute = new TranslatableAttribute(Language::class, [
        'de' => 'Deutsch',
        'en' => 'English',
    ]);

    $count = 0;
    foreach ($translatableAttribute as $language => $translation) {
        if ($language === 'en') {
            expect($translation)
                ->toBe('English');
            $count++;
        }

        if ($language === 'de') {
            expect($translation)
                ->toBe('Deutsch');
            $count++;
        }
    }
    expect($count)->toBe(2);
});

it('can search with scope for translated attribute')->skip();
