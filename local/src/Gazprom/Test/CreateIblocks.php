<?php


namespace Gazprom\Test;


class CreateIblocks
{
    protected $idType;
    protected $idIblock;
    protected $iblockProps = array();

    public function __construct()
    {
        if ($this->createTypeBlock()) {
            $result = $this->createIblock();
        }

		if ($result) {
            $props = $this->setIblockProps();
            foreach ($props as $arFields) {
                $this->addProps($arFields);
            }
            $data = $this->setFilials();
            foreach ($data as $arFilial) {
                $this->fillIblock($arFilial);
            }
		}
    }

    protected function createTypeBlock()
    {
        $arFieldsIblockType = Array(
            'ID'=>'map',
            'SECTIONS'=>'Y',
            'IN_RSS'=>'N',
            'SORT'=>100,
            'LANG'=>Array(
                'ru'=>Array(
                    'NAME'=>'Карта офисов',
                    'SECTION_NAME'=>'Карта офисов',
                    'ELEMENT_NAME'=>'Офис'
                ),
                'en'=>Array(
                    'NAME'=>'Map of offices',
                    'SECTION_NAME'=>'Map of offices',
                    'ELEMENT_NAME'=>'Office'
                )
            )
        );

        $obBlocktype = new \CIBlockType;
        $res = $obBlocktype->Add($arFieldsIblockType);
        if(!$res) {
            echo 'Error: '.$obBlocktype->LAST_ERROR.'<br>';
            return false;
        }
        else {
            $this->idType = $res;
            echo '<p>Тип инфоблока "Карта офисов" создан</p>';
            return true;
        }
    }

    protected function createIblock()
    {
        $arAccess = array(
            "2" => "R",
        );

        $iblock = new \CIBlock;
        $arFields = array(
            "ACTIVE" => 'Y',
            "NAME" => 'Офисы на карте',
            "CODE" => 'map_of_offices',
            "LIST_PAGE_URL" => '/map/',
            "DETAIL_PAGE_URL" => '/map/',
            "IBLOCK_TYPE_ID" => $this->idType,
            "SITE_ID" => array("s1"),
            "SORT" => 100,
            "GROUP_ID" => $arAccess,
        );

        $ID = $iblock->Add($arFields);
        if ($ID > 0) {
            echo "&mdash; инфоблок 'Офисы на карте' успешно создан<br />";
			$this->idIblock = $ID;
            return true;
        }
        else {
            echo "&mdash; ошибка создания инфоблока  'Офисы на карте'<br />";
            return false;
        }
    }

    protected function setIblockProps() {

        $arFields[] = Array(
            "NAME" => "Телефон",
            "ACTIVE" => "Y",
            "SORT" => 500,
            "CODE" => "PHONE",
            "PROPERTY_TYPE" => "S",
            "FILTRABLE" => "Y",
            "IBLOCK_ID" => $this->idIblock,
        );

        $arFields[] = Array(
            "NAME" => "Email",
            "ACTIVE" => "Y",
            "SORT" => 500,
            "CODE" => "EMAIL",
            "PROPERTY_TYPE" => "S",
            "FILTRABLE" => "Y",
            "IBLOCK_ID" => $this->idIblock,
        );

        $arFields[] = Array(
            "NAME" => "Координаты",
            "ACTIVE" => "Y",
            "SORT" => 500,
            "CODE" => "COORD",
            "PROPERTY_TYPE" => "S",
            "FILTRABLE" => "Y",
            "IBLOCK_ID" => $this->idIblock,
        );

        $arFields[] = Array(
            "NAME" => "Город",
            "ACTIVE" => "Y",
            "SORT" => 500,
            "CODE" => "CITY",
            "PROPERTY_TYPE" => "S",
            "FILTRABLE" => "Y",
            "IBLOCK_ID" => $this->idIblock,
        );

        return $arFields;
       
    }

    protected function addProps(array $arFields) {
        $ibp = new \CIBlockProperty;
        $propId = $ibp->Add($arFields);
        if ($propId > 0)
        {
            echo "&mdash; Добавлено свойство ".$arFields["NAME"]."<br />";
        }
        else
            echo "&mdash; Ошибка добавления свойства ".$arFields["NAME"]."<br />";
    }

    protected function setFilials() {
        $data[] = array (
            'NAME' => 'Офис "Маяковский"',
            'PROP' => [
                'PHONE' => '8 800 125-85-78',
                'EMAIL' => 'maykovsky@mail.ru',
                'COORD' => '55.814416, 37.592366',
                'CITY' => 'Москва',
            ],
        );
        $data[] = array (
            'NAME' => 'Офис "Петроградский"',
            'PROP' => [
                'PHONE' => '8 800 300-85-78',
                'EMAIL' => 'petrogradsky@mail.ru',
                'COORD' => '59.966768, 30.286772',
                'CITY' => 'Санкт-Петербург',
            ],
        );
        $data[] = array (
            'NAME' => 'Офис "Ижевский"',
            'PROP' => [
                'PHONE' => '8 800 500-85-78',
                'EMAIL' => 'izhevsky@mail.ru',
                'COORD' => '56.863409, 53.238215',
                'CITY' => 'Ижевск',
            ],
        );
        $data[] = array (
            'NAME' => 'Офис "Магнитогорский"',
            'PROP' => [
                'PHONE' => '8 800 500-85-78',
                'EMAIL' => 'magnitogorsk@mail.ru',
                'COORD' => '53.420463, 58.962172',
                'CITY' => 'Магнитогорск',
            ],
        );
        $data[] = array (
            'NAME' => 'Офис "Омский"',
            'PROP' => [
                'PHONE' => '8 800 700-85-78',
                'EMAIL' => 'omsk@mail.ru',
                'COORD' => '55.061462, 73.249994',
                'CITY' => 'Омск',
            ],
        );

        return $data;
    }

    protected function fillIblock(array $data) {
        $el = new \CIBlockElement;
        $arLoad = Array(  
           'MODIFIED_BY' => $GLOBALS['USER']->GetID(),
           'IBLOCK_SECTION_ID' => false,
           'IBLOCK_ID' => $this->idIblock,
           'PROPERTY_VALUES' => $data['PROP'],  
           'NAME' => $data['NAME'],  
           'ACTIVE' => 'Y',
        );

        if($ID= $el->Add($arLoad)) {
            echo "&mdash; Добавлен офис ".$data["NAME"]."<br />";
        } else {
             echo "&mdash; Ошибка добавления офиса ".$data["NAME"]." ".$el->LAST_ERROR."<br />";
        }
    }


}