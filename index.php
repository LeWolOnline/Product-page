<?php
//Определяем рабочую папку(для вывода изображений):
$path = dirname(__FILE__);

// Подключаемся к базе данных
$host = 'mysql:host=localhost;dbname=test_db; charset=UTF8';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO($host, $user, $pass);
} catch (PDOException $e) {
    // Ошибка подключения
    echo "Ошибка подключения базы данных: $e";
}

// ***Организуем постраничный вывод***

// Узнаем количество выводящихся элементов:
$sql_total = 'SELECT COUNT(id) FROM catalog_products';
$total_rows = $pdo->query($sql_total);
foreach ($total_rows as $key => $value) {
  $count_total = $value[0];
}

// Определяем текущую страницу:
$cur_page = 1;
if (isset($_GET['page']) && $_GET['page'] > 0)
{
    $cur_page = $_GET['page'];
}
// Количество элементов на странице:
$limit_str = '20';
// Текущий элемент, с которого начинаем вывод:
$start = ($cur_page - 1) * $limit_str;
// Узнаем количество страниц, округляя в большую сторону:
$num_pages = ceil($count_total / $limit_str);
// Составляем запрос с учетом лимита:
$sql = sprintf('SELECT
  catalog_products.id as id,
  catalog_products.name as name,
  catalog_products.price as price,
  catalog_products.existence as existence,
  catalog_products.image as image,
  brands.name as brand_name
  FROM catalog_products
  JOIN brands ON brands.id = catalog_products.brand_id
  ORDER BY existence, price
  LIMIT %d, %d', $start, $limit_str);


// подключаем файл для вывода html
include __DIR__ . '/templates/index.phtml';
