<?php

class DB
{
    static public $db = null;

    static function get_DB()
    {
        if(self::$db === null)
        {

            $include_flag = include(__DIR__ . '/credentials.php');
            if($include_flag === false) return false;

            $options = array(
                "PDO::ATTR_PERSISTENT" => true
            );

            $dsn = "mysql:host={$host};dbname={$db_name}";
            try
            {
                self::$db = new PDO($dsn, $user, $pw, $options);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // turn on exception error handling
                self::$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            }
            catch( PDOException $e)
            {
                print $e->getMessage();
                trigger_error('Connection to MySQL database failed. Error: ' . $e->getMessage());
                self::$db = null;
                return false;
            }
        }
        return self::$db;
    }

    static function query($query, $bind_array=array())
    {
        try
        {
            $stmt = self::get_DB()->prepare($query);
            $stmt->execute($bind_array);
            return $stmt;
        }
        catch(PDOException $e)
        {
            print $e->getMessage();
            trigger_error('Error on database query: ' . $e->getMessage());
            return false;
        }
    }

    static function disconnect()
    {
        self::$db=null;
    }

}

