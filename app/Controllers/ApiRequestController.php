<?php

namespace App\Controllers;

use App\Models\Satellite;
use App\Models\ApiRequest;
use DateTimeImmutable;
use PDOException;

class ApiRequestController
{
    public function fetchSatelliteData(array $params)
    {
        $satelliteId = $params['id']; // Holt die 'id' aus der URL

        // API-URL basierend auf der Satellite ID dynamisch anpassen
        $apiName = 'http://api.open-notify.org/iss-now.json';  // ISS URL
        if ($satelliteId !== 'iss') {
            // Hier mit der 'id' die richtige URL für andere Satelliten aufbauen
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
                try {
                    // SatelliteId von der Satellites tabelle holen
                    $satellite = Satellite::findByName($satelliteId);
                    if ($satellite !== null) {
                        $satelliteIdFromDB = $satellite->getId();
                    } else {
                        throw new \RuntimeException('Der Satellit mit dem Namen {$satelliteId}, hat noch keinen eintrag in der Satellites-Tabelle.', 0);
                    }
                    //Timestamp vorbereiten und created At vorbereiten
                    $timestamp = $data['timestamp'] ?? time();
                    $createdAt = date('Y-m-d H:i:s');
                    // Array vorbereiten für DatenbankInsert
                    $data = [
                        'satellite_id' => $satelliteIdFromDB,
                        'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null, // Wenn kein Benutzer angemeldet, setze auf null
                        'latitude' => $data['iss_position']['latitude'],
                        'longitude' => $data['iss_position']['longitude'],
                        'altitude_km' => $data['iss_position']['altitude'] ?? 0.0,
                        'velocity_kmh' => $data['iss_position']['velocity'] ?? 0.0,
                        'timestamp' => $timestamp,
                        'raw_response' => json_encode($data),
                        'created_at' => $createdAt
                    ];

                    // Wenn kein Benutzer vorhanden ist, könnte es sinnvoll sein, eine Fehlerbehandlung hinzuzufügen:
                    if ($data['user_id'] === null) {
                        throw new \RuntimeException('Kein gültiger Benutzer in der Sitzung gefunden.');
                    }

                    $ApiRequestObject = new ApiRequest($data);
                    $success = $ApiRequestObject->create($data);

                    if (!empty($success)) {
                        $_SESSION['ApiRequestStored'] = 'API-Request wurde in die Datenbank eingetragen.';
                    }
                } catch (PDOException $e) {
                    throw new \RuntimeException('Fehler beim Speichern der API-Daten in die Datenbank.', 0, $e);
                }
            } else {
                echo 'Ungültige JSON-Antwort: ' . json_last_error_msg();
            }
        }

        // cURL Verbindung schließen
        curl_close($ch);
    }
}
