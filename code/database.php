<?php

class Database {
    // БД
    private mysqli $db;
    // подключение к БД web, пароль helloworld
    public function connectToDatabase(): void
    {
        $this->db = new mysqli('db', 'root', 'helloworld', 'web');
    }
    // создание таблицы bulletins в БД web
    public function createBulletinsTable(): void
    {
        $table = "CREATE TABLE IF NOT EXISTS web.bulletins
        (
            id int auto_increment unique,
            email varchar(255) not null,
            title varchar(255) not null,
            description mediumtext not null,
            category varchar(255) not null,
            created datetime not null default NOW(),
            constraint ad_pk
                primary key (id)
        )";

        $this->db->query($table);
    }

    // создание таблицы categories в БД web
    public function createCategoriesTable(): void
    {
        $table = "CREATE TABLE IF NOT EXISTS web.categories
        (
            id int auto_increment unique,
            category varchar(255) not null,
            constraint ad_pk
                primary key (id)
        )";

        $this->db->query($table);
    }

    // добавление объявления в таблицу bulletins
    public function addNewBulletin($newBulletin): void
    {
        $table = "INSERT INTO bulletins(email, title, description, category) VALUES
        (
            '{$newBulletin->getEmail()}',
            '{$newBulletin->getTitle()}',
            '{$newBulletin->getDescription()}',
            '{$newBulletin->getCategory()}'
        )";
        $this->db->query($table);
    }
    // извлечение всех объявлений из таблицы bulletins
    public function getBulletins(): mysqli_result|bool
    {
        $query = "SELECT * FROM bulletins";
        $bulletinsFetched = $this->db->query($query);
        return $bulletinsFetched;
    }
    // проверка, есть ли категории в таблице categories
    public function areCategoriesEmpty(): bool
    {
        return empty($this->getCategories());
    }

    // получение всех категорий из таблицы categories
    public function getCategories(): array
    {
        $categories = array();
        $query = "SELECT * FROM categories";
        $result = $this->db->query($query);

        while ($row = $result->fetch_array(MYSQLI_ASSOC))
            array_push($categories, $row['category']);

        return $categories;
    }

    // добавление категории в таблицу categories
    public function addNewCategory($newCategory): void
    {
        $query = "INSERT INTO categories (category) VALUES ('$newCategory')";
        $this->db->query($query);
    }
}