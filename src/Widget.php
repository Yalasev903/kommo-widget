<?php
namespace App;

use PDO;

class Widget {
    private $pdo;

    public function __construct() {
        // Настройка соединения с базой данных
        $host = 'localhost';
        $db = 'kommo_widget';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    // Проверка доступности задачи
    public function checkTaskAvailability($userId, $dateTime) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM tasks WHERE user_id = :user_id AND date_time = :date_time');
        $stmt->execute(['user_id' => $userId, 'date_time' => $dateTime]);
        $count = $stmt->fetchColumn();

        return $count == 0;
    }

    // Сохранение задачи
    public function saveTask($userId, $dateTime) {
        if ($this->checkTaskAvailability($userId, $dateTime)) {
            $stmt = $this->pdo->prepare('INSERT INTO tasks (user_id, date_time) VALUES (:user_id, :date_time)');
            return $stmt->execute(['user_id' => $userId, 'date_time' => $dateTime]);
        }
        return false;
    }
}
?>
