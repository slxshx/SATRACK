# SATRACK

SATRACK ist ein schlankes MVC-Projekt in PHP, das externe API-Daten zu Satellitenanfragen verarbeitet, speichert und übersichtlich darstellt. Ziel ist es, API-Requests nachvollziehbar zu machen, die Ergebnisse in einer Datenbank zu persistieren und dem Nutzer eine einfache Möglichkeit zur Verwaltung und Einsicht zu geben.

## Features

- Empfang und Speicherung externer API-Requests
- Anbindung an eine relationale Datenbank (MySQL)
- Zentrale Modellstruktur mit einer vererbbaren Model-Basis (CRUD)
- Nutzung des MVC-Prinzips zur klaren Trennung von Logik, Darstellung und Daten
- Automatischer Datenbankzugriff über PDO Singleton
- Erweiterbares System mit Fokus auf Skalierbarkeit und sauberen Code

## Verwendete Technologien

- **PHP** (OOP, PDO, Anonymous Functions)
- **MySQL** für die Datenhaltung
- **Composer** (geplant oder optional für Autoloading)
- **.env-Konfiguration** über `$_ENV` Variablen
- **Modularer Aufbau** nach MVC-Struktur (Model, View, Controller)

---

> Weitere Details zur Einrichtung und Nutzung folgen in einer späteren Version.
