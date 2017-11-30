<?php

namespace eliza;

class Utils {
    
    
    public static function queryDatabase($_query, $connection = 'Mysql') {
        static $DatabaseConnection;
        
        if (!$DatabaseConnection)
            $DatabaseConnection = new \PDO(
                'mysql:host=' . GlobalContext::Configuration()->$connection->Hostname
                . ';dbname=' . GlobalContext::Configuration()->$connection->Database
                , GlobalContext::Configuration()->$connection->Username
                , GlobalContext::Configuration()->$connection->Password
                , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        
        
        // DEBUG INFO: returns query result before manipolation
        if (DEBUG) return $DatabaseConnection->query($_query, \PDO::FETCH_ASSOC);
        // DEBUG END
        
        try {
            $Collection = new Collection();
        
            foreach ($DatabaseConnection->query($_query, \PDO::FETCH_ASSOC) as $row) {
                $Collection->append(new Object($row));
            }
            
            return $Collection;
        } catch (\PDOException $E) {
			// returns true when query does not have a dataset but has succeded
            if ($E->getCode() == "HY000") // general error produced by cycling a non-dataset query
                return true;
            else throw $E;        
        }
        
    }
}