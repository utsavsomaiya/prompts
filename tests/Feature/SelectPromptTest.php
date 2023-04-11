<?php

use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;
use function Laravel\Prompts\select;
use Laravel\Prompts\SelectPrompt;

it('accepts an array of labels', function () {
    Prompt::fake([Key::DOWN, Key::ENTER]);

    $result = select(
        label: 'What is your favorite color?',
        options: [
            'Red',
            'Green',
            'Blue',
        ]
    );

    expect($result)->toBe('Green');
});

it('accepts an array of keys and labels', function () {
    Prompt::fake([Key::DOWN, Key::ENTER]);

    $result = select(
        label: 'What is your favorite color?',
        options: [
            'red' => 'Red',
            'green' => 'Green',
            'blue' => 'Blue',
        ]
    );

    expect($result)->toBe('green');
});

it('accepts default values when the options are labels', function () {
    Prompt::fake([Key::ENTER]);

    $result = select(
        label: 'What is your favorite color?',
        options: [
            'Red',
            'Green',
            'Blue',
        ],
        default: 'Green'
    );

    expect($result)->toBe('Green');
});

it('accepts default values when the options are keys with labels', function () {
    Prompt::fake([Key::ENTER]);

    $result = select(
        label: 'What is your favorite color?',
        options: [
            'red' => 'Red',
            'green' => 'Green',
            'blue' => 'Blue',
        ],
        default: 'green'
    );

    expect($result)->toBe('green');
});

it('validates', function () {
    Prompt::fake([Key::ENTER, Key::DOWN, Key::ENTER])
        ->shouldReceive('write')
        ->with(Mockery::on(fn ($value) => str_contains($value, 'You can\'t choose red.')));

    $result = select(
        label: 'What is your favorite color?',
        options: [
            'red' => 'Red',
            'green' => 'Green',
            'blue' => 'Blue',
        ],
        validate: fn ($value) => $value === 'red' ? 'You can\'t choose red.' : null
    );

    expect($result)->toBe('green');
});

it('can fall back', function () {
    Prompt::fallbackWhen(true);

    SelectPrompt::fallbackUsing(function (SelectPrompt $prompt) {
        expect($prompt->label)->toBe('What is your favorite color?');

        return 'Blue';
    });

    $result = select('What is your favorite color?', [
        'Red',
        'Green',
        'Blue',
    ]);

    expect($result)->toBe('Blue');
});
