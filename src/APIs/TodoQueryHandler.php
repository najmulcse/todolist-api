<?php
/**
 * Created by PhpStorm.
 * User: Najmul
 * Date: 4/1/2020
 * Time: 8:05 AM
 */

namespace NAJMULCSE\TODOLIST\APIs;


class TodoQueryHandler {

    private $db = null;

    private $tableName = "todos";

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, title, is_completed, created_at, updated_at
            FROM
                {$this->tableName};
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
               *
            FROM
                {$this->tableName}
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(1, $id);
            if(!$statement->execute()){
              return false;
            }
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function createTable()
    {

        try {

            $this->db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );//Error Handling
            $sql ="CREATE TABLE IF NOT EXISTS ".$this->tableName."(
            id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR( 250 ) NOT NULL,
            is_completed INT (2) DEFAULT (1), 
            created_at DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATE NOT NULL DEFAULT CURRENT_TIMESTAMP );" ;

            $this->db->exec($sql);

        } catch(\PDOException $e) {

            echo $e->getMessage();//Remove or change message in production code
        }
    }
    public function insert(Array $input)
    {

        $query = "SHOW TABLES LIKE " .$this->tableName;
        $table = $this->db->exec($query);
        if(!$table){
            $this->createTable();
        }
        $statement = "
            INSERT INTO {$this->tableName} 
                (title )
            VALUES
                (:title);
        ";

        try {
            $statement = $this->db->prepare($statement);
            // sanitize
            $title = htmlspecialchars(strip_tags($input['title']));
            // bind values
            $statement->bindParam(":title", $title);

            // execute query
            if($statement->execute()){
                return true;
            }

            return false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function update($id, Array $input)
    {
        $updateOption = "title = :title,";
        if(isset($input['is_completed'])){
            $updateOption .= "is_completed  = :is_completed";
        }

        $statement = "
            UPDATE {$this->tableName}
            SET 
                {$updateOption}
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            // sanitize
            $title = htmlspecialchars(strip_tags($input['title']));
            // bind values
            $statement->bindParam(":title", $title);
            if(isset($input['is_completed'])){
                $statement->bindParam(":is_completed", $input['is_completed']);
            }
            $statement->bindParam(':id', $id);
            // execute query
            if($statement->execute()){
                return true;
            }

            return false;

        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM {$this->tableName}
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(":id", $id);

            if($statement->execute()){
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            return false;
        }
    }
}