<?php

class TodoManager {
    private $manager;
    private $database;
    private $collection;

    public function __construct($uri = 'mongodb://localhost:27017', $database = 'mytodoapp', $collection = 'todos') {
        $this->manager = new MongoDB\Driver\Manager($uri);
        $this->database = $database;
        $this->collection = $collection;
    }

    private function getNamespace() {
        return $this->database . '.' . $this->collection;
    }

    public function createTodo($title, $description) {
        $bulk = new MongoDB\Driver\BulkWrite();
        $todo = [
            'title' => $title,
            'description' => $description,
            'completed' => false,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        $bulk->insert($todo);

        try {
            $this->manager->executeBulkWrite($this->getNamespace(), $bulk);
            return true;
        } catch (MongoDB\Driver\Exception\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getTodos() {
        $query = new MongoDB\Driver\Query([]);
        try {
            $cursor = $this->manager->executeQuery($this->getNamespace(), $query);
            $results = [];
            foreach ($cursor as $document) {
                $results[] = $document;
            }
            return $results;
        } catch (MongoDB\Driver\Exception\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function markTodoCompleted($id) {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['completed' => true]]
        );

        try {
            $this->manager->executeBulkWrite($this->getNamespace(), $bulk);
            return true;
        } catch (MongoDB\Driver\Exception\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteTodo($id) {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->delete(['_id' => new MongoDB\BSON\ObjectId($id)]);

        try {
            $this->manager->executeBulkWrite($this->getNamespace(), $bulk);
            return true;
        } catch (MongoDB\Driver\Exception\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>