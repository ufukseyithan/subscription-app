<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\WebsiteController;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/subscribe/{website}', function (Request $request, Website $website) {
    $user = auth()->user();

    if (!$user)
        return "You haven't logged in yet.";

    if ($user->websites()->where('website_id', $website->id)->exists())
        return "You have already subscribed to this website.";

    if ($user) {
        $website->users()->attach($user->id);

        return "You've successfully subscribed to ".$website->url;
    }
});

Route::apiResource('/posts', PostController::class);

Route::get('/websites', [WebsiteController::class, 'index']);

