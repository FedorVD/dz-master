<?php

function addPost(): string
{

    //TODO реализуйте добавление поста в хранилище db.txt
    //Заголовок и тело поста считывайте тут же через readline
    //обработайте ошибки
    //в случае успеха верните тект что пост добавлен

    $fileName = getcwd() . '/db.txt';

    $file = fopen($fileName, 'a+');

    if (!is_writable($fileName)) {
        return handleError("Файл не доступен для записи");
    }

    do {
        $title = readline("Введите заголовок поста: ");
    } while (empty($title));

    do {
        $text = readline("Введите текст поста: ");
    } while (empty($text));

   /* $id = 0;
    while (!feof($file)) {
        fgets($file);
        $id++;
    }*/

    $data = "$title;$text;" . PHP_EOL;

    if (fwrite($file, $data)) {
        fclose($file);
        return "Пост добавлен";
    }

    fclose($file);
    return handleError("Произошла ошибка записи. Данные не сохранены");

}

function readAllPosts(): string
{
    //TODO реализуйте чтение всех постов, но вывести только заголовки
    $fileName = getcwd() . '/db.txt';

    if (!file_exists($fileName)) {
        return handleError("Нет файла с базой db.txt");
    }

    if (!is_readable($fileName)) {
        return handleError("Файл db.txt не читается");
    }

    return file_get_contents($fileName);


}

function readPost(): string
{
    //TODO реализуйте чтение одного поста, номер поста считывайте из командной строки
    $fileName = getcwd() . '/db.txt';

    if (!is_readable($fileName)) {
        return handleError("Файл db.txt не читается");
    }

    do {
        $id = (int)readline("Введите id поста: ");
    } while (empty($id));

    $file = fopen($fileName, 'r');

    while (!feof($file)) {
        $line = fgets($file);
        $post = explode(";", $line);
        if ($post[0] == $id) {
            fclose($file);
            return $line;
        }
    }

    return "Пост с id = $id не найден";
}

function clearPosts(): string
{
    //TODO сотрите все посты

    $fileName = getcwd() . '/db.txt';

    if (!is_writable($fileName)) {
        return handleError("Файл не доступен для записи");
    }

    $file = fopen($fileName, 'w');

    if ($file) {
        fclose($file);
        return 'Все посты удалены';
    }

    fclose($file);
    return handleError('Не удалось открыть файл.');

}

function searchPost(): string
{
    //TODO* реализуйте поиск поста по заголовку (можно и по всему телу), поисковый запрос спрашивайте через readline
    $fileName = getcwd() . '/db.txt';

    if (!is_readable($fileName)) {
        return handleError("Файл db.txt не читается");
    }

    do {
        $subTitle = readline("Введите часть текста заголовка для поиска постов: ");
    } while (empty($subTitle));

    $file = fopen($fileName, 'r');

    while (!feof($file)) {
        $line = fgets($file);
        $post = explode(";", $line);

        if (isset($post[1]) && str_contains($post[1], $subTitle)) {
            echo $line;
        }
    }

    fclose($file);

    return "Поиск завершен";
}

function deletePost(): string
{

    $fileName = getcwd() . '/db.txt';

    if (!is_readable($fileName)) {
        return handleError("Файл db.txt не читается");
    }

    $postsArray = file($fileName);

    if (!$postsArray) {
        return handleError("Список постов пуст. Удалять нечего!");
    }

    echo "Постов в списке " . count($postsArray) . ". Какой хотите удалить?" . PHP_EOL;
    do {
        $id = (int)readline("Введите порядковый номер поста: ");
    } while (empty($id));

    if (!isset($postsArray[$id-1])) {
        return handleError("Поста с таким номером нет!");
    }
    unset($postsArray[$id-1]);

    file_put_contents($fileName, implode( $postsArray));


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