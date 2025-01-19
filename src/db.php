<?php

function getDB(): PDO
{
    static $db = null;
    if (is_null($db)) {
        $db = new PDO("sqlite:database.db");
        $db->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
    }
    return $db;
}

function initDB(): string
{
    $db = getDB();
    $db->query("PRAGMA foreign_keys = ON;");
    $db->query("DROP TABLE IF EXISTS posts;");
    $db->query("DROP TABLE IF EXISTS categories;");
    $db->query("CREATE TABLE IF NOT EXISTS `categories` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
	`category` TEXT NOT NULL
);");
    $db->query("CREATE TABLE IF NOT EXISTS `posts` (
	`id` INTEGER  PRIMARY KEY AUTOINCREMENT UNIQUE,
	`title` TEXT NOT NULL,
	`text` TEXT NOT NULL,
	`id_category` INTEGER,
FOREIGN KEY(`id_category`) REFERENCES `categories`(`id`) ON DELETE RESTRICT);");

    return "init";
}

function seedDB() : string
{
    $db = getDB();
    initDB();
    /*$db->query("DELETE FROM posts ");
    $db->query("DELETE FROM categories ");
    $db->query("INSERT INTO categories VALUES (1, 'News'),(2, 'Politics'),(3, 'Sport')");

    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 1', 'Text text lorem', 1);");
    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 2', 'Text text lorem', 1);");
    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 3', 'Text text lorem', 1);");

    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 4', 'Text text lorem', 2);");
    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 5', 'Text text lorem', 2);");

    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 6', 'Text text lorem', 3);");
    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 6', 'Text text lorem', 3);");
    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 7', 'Text text lorem', 3);");
    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 8', 'Text text lorem', 3);");
    $db->query("INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title 9', 'Text text lorem', 3);");*/
    $db->query("DELETE FROM posts ");
    $db->query("DELETE FROM categories ");
    $querryText = "";
    $stmp="";
    for ($i = 1; $i <= 5; $i++) {
        $querryText = "INSERT INTO categories (id, category) VALUES (".$i.", 'category".$i."');". PHP_EOL;
        $stmp = $db->prepare($querryText);
        $stmp->execute();
        for ($j = 1; $j <= rand(5,10); $j++) {
            $querryText = "INSERT INTO posts (`title`, `text`, `id_category`) VALUES ('Title ".$i.$j."', 'Text post ".$i.$j."',".$i.");".PHP_EOL;
            $stmp = $db->prepare($querryText);
            $stmp->execute();
        }
    }
    return "База данных заполнена";
}

