<?php

use Thettler\ExtendedLocalization\Tests\Fixtures\TestModel;
use Thettler\ExtendedLocalization\TranslatableAttribute;

it('can save Translatable Attribute to database', function () {
    TestModel::create([
        'text' => TranslatableAttribute::new([
            'de' => 'Deutsch',
        ]),
    ]);

    \Pest\Laravel\assertDatabaseHas('test_models', ['text' => '{"de":"Deutsch"}', 'text_nullable' => null,]);
});

it('can get Translatable Attribute from database', function () {
    $model = TestModel::create([
        'text' => TranslatableAttribute::new(['de' => 'Deutsch']),
    ]);

    expect($model->fresh()->text)
        ->toBeInstanceOf(TranslatableAttribute::class)
        ->de->toBe('Deutsch');

    expect($model->fresh()->text_nullable)
        ->toBeNull();
});

it('can use arrays', function () {
    $model = TestModel::create([
        'text' => TranslatableAttribute::new(['de' => ['foo' => 'bar']]),
    ]);

    expect($model->fresh()->text)
        ->toBeInstanceOf(TranslatableAttribute::class)
        ->de->toBe(['foo' => 'bar']);
});

it('can throw error if non nullable attribute is null', function () {
    TestModel::create([
        'text' => null,
    ]);
})->throws(
    \Thettler\ExtendedLocalization\Exceptions\TranslatableAttributeNotNullableException::class,
    'The TranslatableAttribute "text" in Thettler\ExtendedLocalization\Tests\Fixtures\TestModel is not nullable. Try adding nullable to the cast if you want to allow null values: "text" => TranslatableAttribute::class.\':nullable\''
);

it('can parse array to TranslationAttribute', function () {
    $model = new TestModel();

    $model->text = ['de' => 'deutsch'];

    expect($model->text)
        ->toBeInstanceOf(TranslatableAttribute::class)
        ->de->toBe('deutsch');
});

it('can not parse array to TranslationAttribute if language does not exist', function () {
    $model = new TestModel();

    $model->text = ['not_existing' => 'deutsch'];
})->throws(
    \Thettler\ExtendedLocalization\Exceptions\LanguageNotDefinedException::class,
    'Language "not_existing" does not exist in Language Enum. Available languages: en, de'
);

it('can get all translatable attributes', function () {
    $model = new TestModel();

    expect($model->getTranslatableAttributes())->toBe([
        'text',
        'text_nullable',
    ]);
});
it('can check if attribute is translatable', function () {
    $model = new TestModel();

    expect($model->isTranslatableAttribute('text'))->toBeTrue();
    expect($model->isTranslatableAttribute('not_translatable'))->toBeFalse();
});



