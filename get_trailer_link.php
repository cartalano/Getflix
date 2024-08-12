<?php
// Minimum pour utiliser le call a l'api
require_once('vendor/autoload.php');
include_once('config.php');

// Prends l'array obtenue par la fonction Trailer pour chercher le trailer
function getTrailer($response) {
    // Cree le debut de l'url
    $urlYoutube = 'https://www.youtube.com/watch?v=';
    // Cherche dans chaque array UNE clé qui correspond à la fin de l'url et s'arrete quand l'array comprend bien un trailer sur youtube
    foreach ($response['results'] as $media) {
        if ($media['site'] == "YouTube" && $media['type'] == 'Trailer') {
            // Combine l'url avec la cle pour avoir une url valide et la retourne
            return $urlYoutube . $media['key'];
        }
    }
}

//  Sert à obtenir l'array de l'api dans laquelle chercher le trailer
function getTrailerData(int $movieId)
{

    $client = new \GuzzleHttp\Client();
    // Appel à l'api pour chercher les différents médias liés au film
    $response = $client->request('GET', "https://api.themoviedb.org/3/movie/$movieId/videos?language=en-US", [
        'headers' => [
            'Authorization' => 'Bearer ' . MOVIEDB_TOKEN,
            'accept' => 'application/json',
        ],
    ]);
    // Transformer la réponse en array
    $response = json_decode($response->getBody()->getContents(), true);
    // Retourne l'array prete a etre utilise
    return $response;
}
function getTrailerLink($movieId) {
    $response = getTrailerData($movieId);
    return getTrailer($response);
}