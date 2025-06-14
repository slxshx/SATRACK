# SATRACK

SATRACK ist ein flexibles PHP-MVC-Projekt zur Erfassung, Speicherung und Anzeige von Satelliten-bezogenen API-Daten. Es bietet eine saubere Architektur, die sich leicht erweitern lässt – etwa für eigene Analyse-Tools, Nutzerbereiche oder Visualisierungen. Ziel ist es, API-Daten strukturiert zu verwalten und nutzbar zu machen.

## Features (mit Beispielen)

- **Empfang und Speicherung von API-Requests**
  - Beispiel: Ein Request fragt die Position der ISS ab – SATRACK speichert Uhrzeit, Position und Nutzereingaben automatisch in der Datenbank.

- **Flexible Datenbankanbindung via PDO-Singleton**
  - Beispiel: Egal ob Satellitendaten, Nutzerinfos oder Logs – alle Tabellen können über zentrale Model-Logik angesprochen werden.

- **Zentrale CRUD-Modelklasse**
  - Beispiel: Ein neues Model für „Missionslogs“ benötigt nur den Tabellennamen – Methoden wie `create($data)` oder `find($id)` sind sofort nutzbar.

- **Modularer MVC-Aufbau**
  - Beispiel: Du möchtest ein Dashboard anzeigen, das die letzten 10 API-Requests grafisch darstellt? Controller holt die Daten, View rendert das Ergebnis, Model bleibt unabhängig.

- **Skalierbarkeit durch klare Trennung von Logik**
  - Beispiel: Später können eigene REST-Endpunkte geschaffen werden – z. B. `/api/track?lat=...&lon=...` – die exakt auf dein Datenmodell zugreifen.

- **(Zukünftig) Nutzer-Auth & Logging**
  - Beispiel: Jeder Nutzer könnte eigene Track-Requests senden und einsehen. Admins sehen, wie viele Anfragen welcher Nutzer in welchem Zeitraum getätigt hat.

- **Fehlerhandling und Exceptions durch zentralisierte PDO-Verwaltung**
  - Beispiel: Wenn ein API-Request scheitert (z. B. Timeout), wird dies sauber geloggt – inklusive Fehlermeldung und Request-Details.

## Verwendete Technologien

- **PHP** (mit Fokus auf OOP & saubere Klassenstrukturen)
- **MySQL** für persistente Speicherung
- **PDO** für sicheren Datenbankzugriff
- **MVC-Struktur** für klare Verantwortlichkeiten
- **.env-Datei** zur Konfiguration sensibler Daten

---

> ⚙️ Setup-Anleitung folgt bald!  
> 📡 Später geplant: REST-API, User Interface, Logging-Dashboard, Geo-Kartendarstellung.
