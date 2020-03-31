<?php
/**
 * Created by PhpStorm.
 * User: Najmul
 * Date: 3/30/2020
 * Time: 8:17 AM
 */
namespace Wedevs\TodoList\Object;

use PDO;
use PDOException;


class Todo{

    // database connection and table name
    private $conn;
    private $table_name = "todos";

    // object properties
    public $id;
    public $title;


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products

    public function read(){

        // select all query
        $query = "SELECT * FROM " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
    // create product

    public function createTable()
    {

        try {

            $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
            $sql ="CREATE TABLE IF NOT EXISTS ".$this->table_name."(
            id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR( 250 ) NOT NULL,
             is_completed INT (2) DEFAULT (1) NOT NULL;" ;
            $this->conn->exec($sql);

        } catch(PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
    }
    public function create(){

        $query = "SHOW TABLES LIKE " .$this->table_name;
        $table = $this->conn->exec($query);
        if(!$table){
            $this->createTable();
        }
        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                title=:title";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));

        // bind values
        $stmt->bindParam(":title", $this->title);


        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }

    // update the product
    function    update(){

        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                title = :title,
              
            WHERE
                id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title=htmlspecialchars(strip_tags($this->title));

        // bind new values
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }
    // delete the product
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }
    // used when filling up the update product form
    function readOne(){

        // query to read single record
        $query = "SELECT * FROM  " . $this->table_name . " WHERE id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->title = $row['title'];
    }
}