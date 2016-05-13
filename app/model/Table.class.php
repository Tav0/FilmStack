<?php
class Table {
    protected $connection = NULL;

    public function __construct($db) {
        $this->connection = $db->connection();
    }

	//Calls a sql statement using a PDO
    protected function makeStatement($sql, $params=NULL) {
        $statement = $this->connection->prepare($sql);

        try {
			$statement->execute($params);
        } catch (Exception $ex) {
            $errorMessage = "<pre>Tried to run this SQL: {$sql}. Exception: {$ex}</pre>";
            trigger_error($errorMessage);
        }

        return $statement;
    }
}
