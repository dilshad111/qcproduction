<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Update the authenticated user's theme preference
     */
    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:staradmin,softui_sales,sneat_sales'
        ]);

        $user = auth()->user();
        if ($user) {
            $user->update(['theme' => $request->theme]);
        }

        return back()->with('success', 'Theme updated successfully!');
    }

    /**
     * Get available themes
     */
    public function getThemes()
    {
        return [
            'staradmin' => [
                'name' => 'Star Admin 2',
                'description' => 'Modern Bootstrap Admin Template',
                'color' => '#6366f1'
            ],
            'softui_sales' => [
                'name' => 'Soft UI (Sales)',
                'description' => 'Elegant design from Sales project',
                'color' => '#7928ca'
            ],
            'sneat_sales' => [
                'name' => 'Sneat (Sales)',
                'description' => 'Clean design from Sales project',
                'color' => '#5f61e6'
            ]
        ];
    }
}
