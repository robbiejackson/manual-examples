<?php

namespace My\Module\CacheDemo\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Cache\Controller\CallbackController;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;

class CacheDemoHelper implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;
    
    public function getTableCounts(CallbackController $cacheController)
    {
        $myquery = function ($db)
        {   // This callback function gets the number of records in each of the tables below
            // It then sorts them into descending order of totals
            
            // Uncomment the following 2 lines to see that Joomla caches the output as well
            //echo "<br>Performing the queries ...<br>";
            //echo date("H:i:s");
            
            $tables = array("#__assets", "#__overrider", "#__content", "#__extensions", "#__menu", "#__updates", "#__ucm_content", "#__finder_terms_common");
            foreach ($tables as $name)
            {
                $query = $db->getQuery(true)
                    ->select('count(*)')
                    ->from($name);
                $db->setQuery($query);
                $totals["$name"] = $db->loadResult();
            }
            arsort($totals);
            return $totals;
        };

        $db = $this->getDatabase();

        try
        {
            $results = $cacheController->get($myquery, array($db), "table counts");
        }
        catch (\Exception $e)
        {
            echo "<br>Cache Exception!<br>";
            echo $e->getMessage();
            
            $results = $myquery($db);
        }

        return $results;
    }
}