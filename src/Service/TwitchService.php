<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchService
{
    private $httpClient;
    private $clientId;
    private $clientSecret;

    // Symfony va injecter automatiquement ces éléments grâce au fichier services.yaml
    public function __construct(HttpClientInterface $httpClient, string $clientId, string $clientSecret)
    {
        $this->httpClient = $httpClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    // Récupère les streams en direct pour une liste d'identifiants de jeux.
    // $gameIds Tableau d'IDs Twitch (ex: [516575, 32399])
    
    public function getStreamsByGameIds(array $gameIds): array
    {
        // AUTHENTIFICATION : On demande un jeton d'accès (Access Token) à Twitch.
        // On utilise la méthode POST pour envoyer nos identifiants secrets.
        $response = $this->httpClient->request('POST', 'https://id.twitch.tv/oauth2/token', [
            'body' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials', // // Mode "Application" sans compte utilisateur précis
            ],
        ]);
        // On transforme la réponse JSON en tableau PHP et on extrait le Token.
        $token = $response->toArray()['access_token'];

        // 1. array_map : Parcourt ton tableau d'IDs (ex: [123, 456]) et ajoute "game_id=" devant chaque nombre.
        //    On obtient : ["game_id=123", "game_id=456"]
        // 2. implode : Colle ces morceaux ensemble avec un "&" entre chaque.
        //    On obtient la chaîne finale : "game_id=123&game_id=456"
        // 3. Pourquoi ? Parce que Twitch exige ce format précis dans l'URL pour renvoyer les lives de plusieurs jeux à la fois.
        $urlParams = implode('&', array_map(fn($id) => "game_id=$id", $gameIds));

        // Appel à l'API Helix.
        // language=fr : n'affiche que les streamers parlant français.
        // first=6 : limite le résultat aux 6 premiers lives (les plus populaires).
        $streamsResponse = $this->httpClient->request('GET', 'https://api.twitch.tv/helix/streams?' . $urlParams . '&language=fr&first=3', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token, // On fournit le Token
                'Client-Id' => $this->clientId,       // On rappelle l'ID client public
            ],
        ]);

        // On retourne uniquement la clé ['data'] qui contient la liste des streams.
        return $streamsResponse->toArray()['data'];
    }
}