<?php

class TextProcessingService {

    public static function fetchGitHubRepositories($subject) {
        // Preprocesăm subiectul pentru a elimina cuvintele comune și caracterele speciale
        $subject = self::preprocessSubject($subject);
        $keywords = array_filter(explode(' ', $subject));
        $queryString = implode('+', $keywords);

        // Configurați URL-ul și parametrii pentru a accesa API-ul GitHub
        $apiUrl = 'https://api.github.com/search/repositories';
        $queryParams = http_build_query([
            'q' => $queryString,
            'sort' => 'stars',
            'order' => 'desc',
        ]);
        $url = "{$apiUrl}?{$queryParams}";

        // Logare interogare
        error_log("GitHub API Query: " . $url);

        // Realizăm o cerere HTTP GET către API-ul GitHub
        $options = [
            'http' => [
                'header' => "User-Agent: ResourceFinderBot", // GitHub API requires User-Agent header
                'method' => 'GET',
            ],
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        // Logare răspuns
        error_log("GitHub API Response: " . $response);

        if ($response === false) {
            return [];
        }

        $data = json_decode($response, true);
        if (isset($data['items'])) {
            return $data['items'];
        } else {
            return [];
        }
    }

    public static function preprocessSubject($subject) {
        // Lista cuvintelor comune în limba română și engleză care nu sunt relevante pentru căutare
        $stopWords = ['și', 'să', 'în', 'la', 'pe', 'cu', 'un', 'o', 'de', 'ce', 'este', 'am', 'a', 'the', 'and', 'of', 'to', 'with', 'for', 'in', 'on', 'at', 'by'];

        // Descompunem subiectul în cuvinte, eliminăm caracterele speciale și cuvintele comune
        $words = array_filter(explode(' ', preg_replace('/[^\w\s]/u', '', $subject)), function($word) use ($stopWords) {
            return !in_array(mb_strtolower($word), $stopWords);
        });

        return implode(' ', $words);
    }


}
?>
