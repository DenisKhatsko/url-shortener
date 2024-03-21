<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DatabaseExeption;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShortUrlRequest;
use App\Http\Resources\UrlResource;
use App\Services\UrlShortenerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Knuckles\Scribe\Attributes\Response;


class UrlShortenerController extends Controller
{

    /**
     * Endpoint to create new short link record.
     *
     * @response {
     * "short_url": "http://127.0.0.1:8000/short/mgrAPF",
     * "url": "https://www.google.com/",
     * "visits_count": 0,
     * "created_at": "2024-03-21 07:21:46",
     * "updated_at": "2024-03-21 07:21:46"
     * }
     */
    public function store(ShortUrlRequest $request)
    {
        $validated = $request->validated();
        try {
             $result = UrlShortenerService::makeLink($validated['url']);

        } catch (DatabaseExeption|\Exception $e) {
            Log::channel('api')->error($e->getMessage());

            return response()->setStatusCode(500);
        }

        if ($result) {
            return (new UrlResource($result));
        }
        return response()->not_found();
    }

    /**
     * Endpoint to return the specified link match.
     *
     * @response {
     *  "short_url": "http://127.0.0.1:8000/short/mgrAPF",
     *  "url": "https://www.google.com/",
     *  "visits_count": 1,
     *  "created_at": "2024-03-21 07:21:46",
     *  "updated_at": "2024-03-21 07:22:21"
     * }
     */
    public function show(string $shortUrl)
    {
        try {
            $result = UrlShortenerService::getDestinationUrl($shortUrl);

        } catch (ModelNotFoundException) {
            return response()->not_found();
        }

        return (new UrlResource($result));
    }


}
