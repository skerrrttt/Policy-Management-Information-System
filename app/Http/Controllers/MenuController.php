<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;



class MenuController extends Controller
{
    public function getMenu()
    {
        $user = Auth::user();

    // Define menu items
    $menu = [
        [
            'url' => '/submit-proposal',
            'name' => 'Submit Proposal',
            'icon' => 'menu-icon tf-icons bx bx-send',
            'slug' => 'submit-proposal'
        ],
        [
            'url' => '/proposal-tracker',
            'name' => 'Proposal Tracker',
            'icon' => 'menu-icon tf-icons bx bx-line-chart',
            'slug' => 'proposal-tracker'
        ],
        [
            'url' => '/logs',
            'name' => 'Logs',
            'icon' => 'menu-icon tf-icons bx bx-clipboard',
            'slug' => 'logs'
        ],
        [
            'url' => '/localsec/meetings',
            'name' => 'Meetings',
            'icon' => 'menu-icon tf-icons bx bx-calendar-event',
            'slug' => 'localsec-meetings',
            'roles' => ['Local Secretary']
        ]
    ];

    // Get user position
    $userPosition = $this->getUserPosition($user);

    // Filter the menu based on user position
    $filteredMenu = array_filter($menu, function ($menuItem) use ($userPosition) {
        return !isset($menuItem['roles']) || in_array($userPosition, $menuItem['roles']);
    });

    return response()->json(['menu' => array_values($filteredMenu)]);
}
}