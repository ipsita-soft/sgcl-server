<?php

arch('Do not leave debug statements')
    ->expect(['dd', 'dump', 'var_dump'])
    ->not->toBeUsed();

arch('Do not use env helper in code')
    ->expect(['env'])
    ->not->toBeUsed();

arch('Models should be within App\Models')
    ->expect('App\Models')
    ->toBeClasses();

arch('Model extends way')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

arch('Enums should be within App\Enums')
    ->expect('App\Enums')
    ->toBeEnums();

arch('Traits should be within App\Traits')
    ->expect('App\Traits')
    ->toBeTraits();
