<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

 use Bitrix\Main\Loader;
        Loader::includeModule("iblock");

class mapOffices extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        /*$result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
        );*/
        return $arParams;
    }

    public function getOffices($arParams)
    {
        $arOffices = array();

        $arProp = array (
            'PHONE','EMAIL','COORD','CITY'
        );

        $key = md5('map_office');

        $cache = \Bitrix\Main\Data\Cache::createInstance();

        if ($cache->initCache(36000, $key)) {
            $arOffices = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $dbItems = \Bitrix\Iblock\ElementTable::getList(array(
                'order' => array('SORT' => 'ASC'),
                'select' => array('ID', 'NAME'),
                'filter' => array('IBLOCK_ID' => $arParams['IBLOCK_ID']),
                'limit' => 200,
                'cache' => array(
                    'ttl' => 36000,
                    'cache_joins' => true
                ),
            ));

            while ($el = $dbItems->fetch()) {
                $arItem = $el;
                $arItem['PROP'] = array();
                foreach($arProp as $nameProp) {
                    $property = \CIBlockElement::getProperty($arParams['IBLOCK_ID'], $el['ID'], array("sort", "asc"), array('CODE' => $nameProp))->GetNext();
                    $arItem['PROP'][$nameProp] = $property['VALUE'];
                    /*echo $nameProp.' - ';
                    print_r($property);*/
                }
                $arOffices[] = $arItem;
            }

            if (count($arOffices) === 0) {
                $cache->abortDataCache();
            }

            $cache->endDataCache($arOffices);
        }

        return $arOffices;
    }

    public function getKey() {

        $map='map_yandex_keys';
        $MAP_KEY = '';
        $strMapKeys = COption::GetOptionString('fileman', $map);
        
        $strDomain = $_SERVER['HTTP_HOST'];
        $wwwPos = strpos($strDomian, 'www.');
        if ($wwwPos === 0)
          $strDomain = substr($strDomain, 4);

        if ($strMapKeys)
        {
          $arMapKeys = unserialize($strMapKeys);
          print_r($arMapKeys);
          
          if (array_key_exists($strDomain, $arMapKeys))
             $MAP_KEY = $arMapKeys[$strDomain];
        }
       
        if (!$MAP_KEY)
        {
          return false;
        }
        else {
            return $MAP_KEY;
        }

    }
}

?>