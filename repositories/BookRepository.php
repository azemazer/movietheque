<?php

require_once "core/IBookRepository.php";
require_once "model/BookModel.php";
require_once "model/AuthorModel.php";
require_once "model/EditionModel.php";
require_once "model/EditorModel.php";

class BookRepository implements IBookRepository
{
    public function getAllBooks(int $author_id = 0, int $editor_id = 0) {
        //PDO
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=bibliobject', "biblio_admin", "123456789");

        } catch( PDOException $e ){
        }
        echo"ye";

        // Request
        $sql = "SELECT b.id AS book_id, b.name AS book_name, b.pageCount, e.name AS editor_name, e.id AS editor_id, a.firstname, a.lastname, a.id AS author_id, en.publishDate  FROM book b
        JOIN creation c ON c.idBook = b.id
        JOIN author a ON c.idAuthor = a.id
        JOIN editions en ON en.idBook = b.id
        JOIN editeurs e ON en.idPublisher = e.id";
        if( $author_id != 0 && $editor_id != 0 ) {
            $sql = $sql . " WHERE a.id = " . strval($author_id) . " AND e.id = " . strval($editor_id);
        }
        else if( $author_id != 0 ) {
            $sql = $sql . " WHERE a.id = " . strval($author_id);
        }
        else if( $editor_id != 0 ) {
            $sql = $sql . " WHERE e.id = " . strval($editor_id);
        }
        $conn = $dbh->prepare($sql);
        $conn->execute();
        $data = $conn->fetchAll();

        // Models
        $res = [];
        $doublons = [];
        foreach($data as $item) {
            $editor = new Editor((object)[
                "id" => $item['editor_id'],
                "name" => $item['editor_name']
            ]);
            $edition = new Edition((object)[
                "publishDate" => $item['publishDate'],
                "editor" => $editor
            ]);
            $author = new Author((object)[
                "id" => $item['author_id'],
                "firstname" => $item['firstname'],
                "lastname" => $item['lastname']
            ]);

            // Check if book already exists
            $previous_book = array_filter($res, function($old_book) use ($item) {
                return $old_book->getId() === $item['book_id'];
            });
            if($previous_book) {
                array_push($doublons, $previous_book);
                
                // Check if it's another author
                $previous_authors = $previous_book[0]->getAuthors();
                $new_author = array_filter($previous_authors, function($authors) use ($previous_authors, $item) {
                    return in_array($item['author_id'], $previous_authors);
                });
                if($new_author){

                }
            }

            $book = new Book((object)[
                "id" => $item['book_id'],
                "title" => $item['book_name'],
                "edition" => $edition,
                "authors" => [$author],
                "pageCount" => $item['pageCount'],
            ]);
            array_push($res, $book);
        }
        echo '<pre>'; var_dump($res); echo '</pre>';
        echo '<pre>'; var_dump($doublons); echo '</pre>';
    }

    public function getBookById(int $book_id){

    }

    public function createBook(string $name, int $author_id, int $edition_id, string $publishing_date) {

    }

    public function updateBook(int $book_id, string $name, int $author_id, int $edition_id) {

    }

    public function deleteBook(int $book_id){

    }
}
