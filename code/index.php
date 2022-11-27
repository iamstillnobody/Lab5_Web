<?php

require_once 'database.php';
require_once 'bulletin.php';

$database = new Database();
$database->connectToDatabase();
$database->createBulletinsTable();
$database->createCategoriesTable();
?>

<!-- форма для добавления новой категории в таблицу categories -->
<html lang="en">
    <form action='index.php' method='post'>

        <label for="category">category:</label>
        <label>
            <input type='text' name='category' required>
        </label>

        <input type='submit' name='submitCategory' value='Submit'>
    </form>
</html>

<!-- добавление новой категории в таблицу categories -->
<?php
if (isset($_POST['submitCategory'])) {
    $category = $_POST['category'];
    $resultCategories = $database->getCategories();

    // needle -- элемент для поиска, haystack -- массив, strict -- строгое/нестрогое сравнение (с учётом/без учёта типа)
    function in_2D_array($needle, $haystack, $strict = false) {
        foreach ($haystack as $item)
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_2D_array($needle, $item, $strict)))
                return true;
        return false;
    }

    // проверка, что предлагаемая для добавления категория не существует
    // если она уже существует, то она не будет добавлена в categories
    if (!$database->areCategoriesEmpty()) {
        if (!in_2D_array($category, $database->getCategories()))
            $database->addNewCategory($category);
    }
    else {
        $database->addNewCategory($category);
    }
}
?>

<!-- форма для добавления нового объявления в таблицу bulletins -->
<html lang="en">
    <form action="index.php" method="post">

        <label for="email">email:</label>
        <label>
            <input type='text' name='email' required>
        </label>

        <label for="title">title:</label>
        <label>
            <input type='text' name='title' required>
        </label>

        <label for="description">description:</label>
        <label>
            <textarea rows="3" cols="25" name="description"></textarea>
        </label>

        <label for="category">category:</label>
            <select name='category' required>
                <?php
                foreach ($database->getCategories() as $c)
                    echo "<option value='$c'>$c</option>";
                ?>
            </select>

        <input type='submit' name='bulletinSubmit' value='Submit'>
    </form>
</html>

<!-- добавление нового объявления в таблицу bulletins -->
<?php
$bulletin = new Bulletin();
if (isset($_POST['bulletinSubmit'])) {
    $bulletin->setEmail($_POST['email']);
    $bulletin->setTitle($_POST['title']);
    $bulletin->setDescription($_POST['description']);
    $bulletin->setCategory($_POST['category']);
    $database->addNewBulletin($bulletin);
}
?>

<!-- вывод таблицы объявлений на странице index.php -->
<html>
    <table>
    <thead>
        <th>email</th>
        <th>category</th>
        <th>title</th>
        <th>description</th>
        </thead>
    <tbody>

    <?php
    $result = $database->getBulletins();
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['description']; ?></td>
        </tr>
        <?php
    }
    ?>

    </tbody>
    </table>
</html>
