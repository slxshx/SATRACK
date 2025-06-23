<?php

namespace App\Core;

use PDO;
use PDOException;

class Model
{
    // PDO-Datenbankinstanz
    protected PDO $db;

    // Name der zugehörigen Datenbanktabelle (wird von Kindklasse gesetzt)
    protected string $table;

    // Konstruktor wird protected, damit nur Kindklassen dieses Model erweitern können
    protected function __construct()
    {
        // Holt sich die (Singleton-)Verbindung zur Datenbank
        $this->db = Database::getInstance();
    }

    // Gibt alle Datensätze aus drei Tabellen zurück (unspezifisch!)
    public function all()
    {
        try {
            // Gibt Daten aller Tabellen zurück
            $sql = "SELECT ar.*, s.name AS satellite_name, u.username AS user_name
            FROM api_requests ar
            LEFT JOIN satellites s ON ar.satellite_id = s.id
            LEFT JOIN users u ON ar.user_id = u.id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \RuntimeException('Fehler beim abrufen der Daten.', 0, $e);
        }
    }

    // Gibt alle Datensätze aus der Tabelle zurück, die zur Kindklasse gehört
    public function allFromThisTable()
    {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \RuntimeException('Fehler beim abrufen der Daten.', 0, $e);
        }
    }

    // Gibt einen einzelnen Datensatz anhand seiner ID zurück
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \RuntimeException('Fehler beim abrufen der Daten.', 0, $e);
        }
    }

    // Erstellt einen neuen Datensatz mit den übergebenen Daten
    public function create($data)
    {
        try {
            // Spaltennamen (z. B. name, speed)
            $columns = implode(',', array_keys($data));

            // Platzhalter für Prepared Statement (z. B. :name, :speed)
            $placeholders = implode(',', array_map(fn($column) => ":$column", array_keys($data)));

            // Vollständiges SQL zusammenbauen
            $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

            $stmt = $this->db->prepare($sql);

            // Direktes Übergabe der Daten an execute() (PDO nutzt die Platzhalter)
            $stmt->execute($data);

            // Gibt zurück, letzte id
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new \RuntimeException('Fehler beim einfügen der Daten.', 0, $e);
        }
    }

    // Aktualisiert einen bestehenden Datensatz anhand der ID
    public function update($id, $data)
    {
        try {
            // Bilde für jeden Key eine Zuweisung: z. B. name = :name
            $setParts = array_map(fn($key) => "$key = :$key", array_keys($data));

            // Zusammenfügen zu: "name = :name, velocity_kmh = :velocity_kmh"
            $setClause = implode(', ', $setParts);

            // SQL-Update-Statement
            $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            // Übergabeparameter zusammenbauen: Daten + ID
            $params = $data;
            $params['id'] = $id;

            $stmt->execute($params);

            // Gibt zurück, wie viele Zeilen betroffen waren
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new \RuntimeException('Fehler beim aktualisieren der Daten.', 0, $e);
        }
    }

    // Löscht einen Datensatz anhand der ID
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new \RuntimeException('Fehler beim löschen der Daten.', 0, $e);
        }
    }
}
