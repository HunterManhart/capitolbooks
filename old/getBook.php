<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = $_POST["isbn"];

    require_once "../db/connect.php";

    if (is_numeric($isbn)) {
        $query = "Select title, author, publisher from books where isbn_13 = ?";

        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("i", $isbn);
        $stmt->execute();
        $stmt->bind_result($title, $author, $publisher);
        $stmt->fetch();

        $isbn_13 = $isbn;
    }

    $html = "";
    if ($title != "") {
        $html = '<div class="book-select">
                    <div class="book-head">
                        <div class="book-title">' .
                            $title
                        . '</div>
                        <div class="book-author">' .
                            $author
                        . '</div>
                    </div>
                    <div class="book-footer">
                        <div class="book-edition">
                            Edition: ' . $edition . '
                        </div>
                        <div class="book-publisher">
                            Publisher: ' . $publisher . '
                        </div>
                        <div class="book-isbn">ISBN13: ' . $isbn . '</div>
                    </div>
                    <button class="book-remove">X</button>
                </div>';
    }

    /*<div class="price price-ours">
                        Our Price:
                        <button class="price-ours sell-now">Sell now</button>
                    </div>*/

    echo $html;

}
?>