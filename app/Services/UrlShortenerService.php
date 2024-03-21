<?php

namespace App\Services;

use App\Exceptions\DatabaseExeption;
use App\Models\ShortUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\URL;



class UrlShortenerService
{
    const CREATE_RETRIES = 5;
    const HASH_LENGTH = 6;

    public static function getDestinationUrl(string $shortUrl)
    {
        return ShortUrl::where('short_url', $shortUrl)->firstOrFail();
    }


    /**
     * @throws DatabaseExeption
     */
    public static function makeLink(string $url)
    {
        $createRetries = config('app.retries');

        $result = false;
        $retry = 0;
        while (!$result) {

            $retry += 1;
            $hash = self::makeHash();

            try {
                $result = ShortUrl::create([
                    'url' => $url,
                    'short_url' => $hash,
                ]);

            } catch (UniqueConstraintViolationException) {
                if ($retry >= $createRetries) {
                    throw new DatabaseExeption('Create attempts exceeds maximum assigned as ' . self::RETRIES);
                }
            }
        }
        return $result;

    }

    public static function updateVisited($link): void
    {
        $link->visits_count +=1;
        $link->save();
    }

    private static function makeHash(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shortCode = '';
        for ($i = 0; $i < self::HASH_LENGTH; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $shortCode .= $characters[$index];
        }
        return $shortCode;

    }
}
