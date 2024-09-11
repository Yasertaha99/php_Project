<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');
 
class DBConnection
{
    private $host, $dbname, $username, $password, $port;
    private $db;
    function __construct($host, $dbname, $username, $password, $port = 3306)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
    }

    function connect($dbtype)
    {
        try
        {
            $dsn = $dbtype.':dbname='.$this->dbname.';host='.$this->host.';port='. $this->port.';';
            $this->db = new PDO($dsn, $this->username, $this->password);
    
        }   
        catch(PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        } 
    }

    function select($tablename)
    {
        try
        {
            // $arr = [];
        $sql = 'select * from '.$tablename;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        // foreach ($result as $row) 
        // {
        //     $arr[] = $row;
        // }
        // return $arr;
        return $result;
        }
        catch (PDOException $e) 
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
        
    }

    function selectWithCondition($tablename, $condittion,$param=[])
    {
        try
        {
            // $arr = [];
        $sql = 'select * from ' . $tablename . ' where ' . $condittion;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($param);
        $result = $stmt->fetchAll();
        // foreach ($result as $row) 
        // {
        //     $arr[] = $row;
        // }
        // return $arr;
        return $result;
        }
        catch (PDOException $e) 
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
        
    }

    // insert , update , delete
    function DML($sql, $params = [])
    {  
        try
        {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            // echo $stmt->rowCount() . ' rows are affected';
            return $this->db->lastInsertId();
            // return $stmt;
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: '. $e->getMessage();
        }
    }


    // function selectWithLimit($tablename, $offset, $limit)
    // {
    //     try
    //     {
    //         // $arr = [];
    //     $sql = 'select * from '.$tablename . 'limit '. $offset .','. $limit;
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetchAll();
    //     return $result;
    //     }
    //     catch (PDOException $e) 
    //     {
    //         echo 'Connection failed: ' . $e->getMessage();
    //     }
        
    // }

    function selectWithLimit($tablename, $offset, $limit, $condition ="")
{
    try
    {
        // Sanitize table name to prevent SQL injection
        $tablename = preg_replace('/[^a-zA-Z0-9_]/', '', $tablename);

        // Prepare the SQL query with placeholders for offset and limit
        $sql = "SELECT * FROM $tablename $condition LIMIT :offset, :limit";
        $stmt = $this->db->prepare($sql);

        // Bind parameters to the SQL query
        // Since LIMIT in MySQL requires both offset and limit to be integers, use positional binding
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
    catch (PDOException $e)
    {
        // Handle exception and show error message
        echo 'Query failed: ' . $e->getMessage();
        return [];
    }
}



    function __destruct()
    {
        $this->db = null;
    }
}
?>