<?php

namespace App\Controllers;

class ApiRequestController
{
    public function fetchSatelliteData(array $params)
    {
        $satelliteId = $params['id']; // Holt die 'id' aus der URL

        // API-URL basierend auf der Satellite ID dynamisch anpassen
        $apiName = 'http://api.open-notify.org/iss-now.json';  // ISS URL
        if ($satelliteId !== 'iss') {
            // Hier kannst du mit der 'id' die richtige URL für andere Satelliten aufbauen
            $apiName = "https://example.com/satellite/$satelliteId"; // Beispiel für andere Satelliten
        }

        // cURL Initialisierung
        $ch = curl_init($apiName);

        // cURL-Optionen setzen
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);  // Header entfernen, nur der Body wird zurückgegeben
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Folgen von Weiterleitungen, falls nötig

        // Request senden und Antwort speichern
        $response = curl_exec($ch);

        // Fehlerbehandlung für den cURL-Request
        if (curl_errno($ch)) {
            echo 'cURL Fehler: ' . curl_error($ch);
        } else {
            // Gib die rohe Antwort aus, um zu sehen, was die API zurückgibt
            echo "<pre>Raw API Response: $response</pre>";

            // JSON-Antwort verarbeiten
            $data = json_decode($response, true);

            // Prüfen, ob das JSON korrekt verarbeitet wurde
            if (json_last_error() === JSON_ERROR_NONE) {
                // Erfolgreiche Verarbeitung
                echo "Daten für Satelliten ID: $satelliteId<br>";
                print_r($data);
            } else {
                echo 'Ungültige JSON-Antwort: ' . json_last_error_msg();
            }
        }

        // cURL Verbindung schließen
        curl_close($ch);
    }
}
