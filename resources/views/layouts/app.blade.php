@php
    $userTheme = Auth::check() ? (Auth::user()->theme ?? 'staradmin') : 'staradmin';
    
    // Map theme names to layout files
    $themeLayouts = [
        'staradmin' => 'layouts.staradmin',
        'softui_sales' => 'layouts.softui_sales',
        'sneat_sales' => 'layouts.sneat_sales',
        // Legacy theme support (old color themes now default to staradmin)
        'light' => 'layouts.staradmin',
        'dark' => 'layouts.staradmin',
        'blue' => 'layouts.staradmin',
        'sunset' => 'layouts.staradmin',
        'forest' => 'layouts.staradmin',
        'glass' => 'layouts.staradmin',
    ];
    
    $selectedLayout = $themeLayouts[$userTheme] ?? 'layouts.staradmin';
@endphp

@extends($selectedLayout)
