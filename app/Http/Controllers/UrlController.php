<?php

namespace App\Http\Controllers;

use App\Services\UrlShortenerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UrlController extends Controller
{
    public function __invoke(string $shortUrl)
    {
        try {
            $link = UrlShortenerService::getDestinationUrl($shortUrl);

        } catch (ModelNotFoundException) {
            return response()->setStatusCode(404);
        }
        UrlShortenerService::updateVisited($link);

        return redirect($link->url, 301);

    }
}
