<?php

namespace App\Models;
use App\DTO;

abstract class BaseModel
{
    protected $conn;
    /** @var string */
    protected $table;
    /** @var array  */
    protected $columns = [];

    public function __construct()
    {
        $this->conn = Connection::getConnection();
    }

    public function insert($data)
    {
        $data = $this->escape($data);
        $sql = "INSERT INTO {$this->table}(".implode(array_keys($data), ",").") VALUES('".implode(array_values($data), "' , '")."')";
        $this->execute($sql);
        return $this->getIdForLastAffectedRow();
    }

    public function update($data, $id)
    {
        $data = $this->escape($data);
        $id = $this->escape($id);
        foreach($data as $columnName => $value)
        {
            $updateColumns[] = "$columnName = '$value'";
        }

        $sql = "UPDATE {$this->table} SET ". implode($updateColumns,' , ') ."WHERE id  = '{$id}'";
        return $this->execute($sql);
    }

    public function deleteById($id)
    {
        $id = $this->escape($id);
        $sql = "DELETE FROM {$this->table} WHERE id = '$id'";
        return $this->execute($sql);

    }

    public function deleteWhere($conditions)
    {
        $where = $this->makeWhere($conditions);
        $sql = "DELETE FROM {$this->table} $where";
        return $this->execute($sql);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $result = $this->execute($sql);
        return DTO::make($result);
    }

    public function getById($id)
    {
        $id = $this->escape($id);
        $sql = "SELECT * FROM {$this->table} WHERE id = $id";
        $result = $this->execute($sql);
        return DTO::make($result);
    }

    public function getWhere($conditions)
    {
        $where = $this->makeWhere($conditions);
        $sql = "SELECT * FROM {$this->table} $where";
        $result = $this->execute($sql);
        return DTO::make($result);
    }

    protected function makeWhere($conditions)
    {
        //        $condetions = [
        //            ['id' , '>', 10],
        //            ['username', '=' ,'Murad'],
        //            ['gender' ,'like' , '%asd%']
        //        ];
        $where = ' WHERE ';
        $count = count($conditions);
        foreach($conditions as $key => [$column , $condition , $value]){
            $value = $this->escape($value);
            $where .= "$column $condition '$value' " ;
            if($key !== $count-1){
                $where .= ' AND ';
            }
        }
        return $where;
    }

    protected function escape($data)
    {
        if (is_array($data)){
            $array = array();
            foreach ( $this->filterColumns($data) as $column => $value ) {
                $array[$column] = mysqli_real_escape_string( $this->conn, $value );
            }
            return $array;
        }
        return mysqli_real_escape_string($this->conn, $data);
    }

    public function execute($query)
    {
        return mysqli_query($this->conn, $query);
    }

    public function getIdForLastAffectedRow()
    {
        return mysqli_insert_id($this->conn);
    }
    public function getAllColumns()
    {
        return $this->columns;
    }
    public function filterColumns($data)
    {
        return array_intersect_key($data, array_flip($this->getAllColumns()));
    }
}
