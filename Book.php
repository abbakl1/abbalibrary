<?php
class Book{
    private $conn;
    public function __construct($db){ $this->conn = $db; }

    public function getBooks(){
        return $this->conn->query("SELECT * FROM books ORDER BY uploaded_at DESC");
    }
}
?>