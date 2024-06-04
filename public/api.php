<?php
require_once '../vendor/autoload.php';
require_once '../src/Widget.php';

use App\Widget;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Конфигурация OAuth для Kommo CRM
$clientId = 'd4774eac-a9ba-4300-bb38-94c13dbe8115';
$clientSecret = 'AE8JUnwFvZe1RGSewDJZLrgSwwbImZyUo5i1dQWRINolIe2hkav0RpdQjLZCzg9O';
$redirectUri = 'http://localhost/kommo-widget/public/api.php';
$tokenUrl = 'https://kommo.com/oauth2/access_token';

// Функция для получения токена доступа
function getAccessToken($authCode) {
    global $clientId, $clientSecret, $redirectUri, $tokenUrl;

    $client = new Client();
    try {
        $response = $client->post($tokenUrl, [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
                'code' => $authCode,
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data;
    } catch (RequestException $e) {
        echo 'Ошибка получения токена доступа: ' . $e->getMessage();
        return null;
    }
}

// Проверка наличия кода авторизации в запросе
if (isset($_GET['code'])) {
    $authCode = $_GET['code'];
    $tokenData = getAccessToken($authCode);

    if ($tokenData) {
        // Сохранение токенов для дальнейшего использования
        $accessToken = $tokenData['access_token'];
        $refreshToken = $tokenData['refresh_token'];
        // Сохраните токены в файл или базу данных
        file_put_contents('tokens.json', json_encode($tokenData));
        echo 'Токен доступа успешно получен и сохранен.';
    } else {
        echo 'Не удалось получить токен доступа.';
    }
    exit;
}

// Обработка POST-запроса для проверки доступности задач и их сохранения
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = $input['user_id'];
    $dateTime = $input['date_time'];

    $widget = new Widget();

    if ($widget->checkTaskAvailability($userId, $dateTime)) {
        if ($widget->saveTask($userId, $dateTime)) {
            echo json_encode(['available' => true, 'message' => 'Задача успешно сохранена.']);
        } else {
            echo json_encode(['available' => false, 'message' => 'Это время уже занято другой задачей.']);
        }
    } else {
        echo json_encode(['available' => false, 'message' => 'Это время уже занято другой задачей.']);
    }
}
?>
