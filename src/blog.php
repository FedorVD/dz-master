<?php

function addPost(): string
{

    $db = getDB();
    $stmp = $db->prepare("SELECT * FROM categories");
    $stmp->execute();
    $categories = $stmp->fetchAll();
    $countCategories = count($categories);
    echo "Список категорий:".PHP_EOL;
    foreach ($categories as $category) {
        //var_dump($category);
        echo $category["category"].PHP_EOL;
    }
    $stmp = $db->prepare("SELECT count(*) FROM posts");
    $countPosts = $stmp->execute();

    do{
        $categoryNumber = (int)readline("Выберите тему поста из списка: 1-5");
    }while($categoryNumber < 1 || $categoryNumber > $countCategories);

    do {
        $title = readline("Введите заголовок поста: ");
    } while (empty($title));

    do {
        $text = readline("Введите текст поста: ");
    } while (empty($text));

    $querryText = "INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('$title', '$text',$categoryNumber);".PHP_EOL;
    $stmp = $db->prepare($querryText);
    $stmp->execute();

    return "Пост добавлен";
}

function readAllPosts(): string
{
    $db = getDB();
    $result = showPost($db->prepare("SELECT p.id post_id, c.category, p.title, p.text FROM posts p join categories c on p.id_category = c.id"));

    return $result;
}

function readPost(): string
{
    $db = getDB();

    do {
        $id = (int)readline("Введите id поста: ");
    } while (empty($id));
    $result = showPost($db->prepare("SELECT p.id post_id, c.category, p.title, p.text FROM posts p join categories c on p.id_category = c.id WHERE post_id=1"));

    return $result;
}

function clearPosts(): string
{
    $db = getDB();
    $stmp = $db->prepare("DELETE FROM posts;");
    $stmp->execute();
    return "Все посты удалены";
}

function searchPost(): string
{

    $db = getDB();
    do {
        $subTitle = readline("Введите часть текста заголовка для поиска постов: ");
    } while (empty($subTitle));

    $result = showPost($db->prepare("SELECT * FROM posts WHERE title LIKE '%$subTitle%';"));
    return $result. PHP_EOL. "Поиск завершен";
}

function showPost($stmp): string
{
    $stmp->execute();
    $resultQuerry = $stmp -> fetchAll();
    $result="N\t Category\t  Title\t\t Text". PHP_EOL;
    foreach ($resultQuerry as $post) {
        $result .= implode("\t", $post) . PHP_EOL;
    }
    return $result;
}

function deletePost(): string
{

    $db = getDB();
    $stmp = $db->prepare("SELECT * FROM posts");
    $stmp->execute();
    $posts = $stmp->fetchAll();
    echo "Постов в списке " . count($posts) . ". Какой хотите удалить?" . PHP_EOL;
    do {
        $id = (int)readline("Введите порядковый номер поста: ");
    } while (empty($id));

    if (!isset($posts[$id-1])) {
        return handleError("Поста с таким номером нет!");
    }
    $stmp = $db->prepare("DELETE FROM posts WHERE id=$id;");
    $stmp->execute();

    return "Пост удален";
}

function quiz() : string
{
    $fileName = getcwd() . "/quiz.json";
    if (!file_exists($fileName)) {
        return handleError("Нет файла с базой quiz.json");
    }
    if (!is_readable($fileName)) {
        return handleError("Файл quiz.json не читается.");
    }
    $file = fopen($fileName, "r");
    $json = fread($file, filesize($fileName));
    $questions = json_decode($json, true);
    $win=TRUE;

    echo "Начинается викторина." . PHP_EOL . "Вам будут заданы вопросы и даны варианты ответов." .PHP_EOL.
        "Надо выбрать правильный ответ." . PHP_EOL . "Как только будет выбран неверный ответ викторина закончится поражением." . PHP_EOL .
        "Если все ответы будут верными, то викторина будет выиграна." . PHP_EOL;
    foreach ($questions as $question) {
        do{
            $countAnswers = count($question["answers"]);
            echo "-----------------------------" . PHP_EOL;
            echo $question["question"] . PHP_EOL;
            echo "Выберите номер правильного ответа:" . PHP_EOL;
            echo "-----------------------------" . PHP_EOL;
            $i = 1;
            foreach ($question["answers"] as $answer) {
                echo $i++, " => ", $answer . PHP_EOL;
            }
            $userAnswer = (int)readline("Ваш ответ: ");
            $checkInput = $userAnswer >=1 && $userAnswer <= $countAnswers;
            if (!$checkInput) {
                echo "Ошибка! Введите число от 1 до $countAnswers" . PHP_EOL;
            }
        } while (!$checkInput);
        if ($userAnswer!= $question["correct"]) {
            $win=false;
            break;
        }
    }
    if (!$win) {
        return "Ответ не верный! Вы проиграли!" . PHP_EOL;
    }

    return "Вы выиграли в викторине!" .PHP_EOL. "Количество правильных ответов ". count($questions) . PHP_EOL;

}