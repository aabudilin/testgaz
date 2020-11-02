<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult["KEY"] = Bitrix\Main\Config\Option::get('fileman', 'yandex_map_api_key');

if($this->startResultCache())
{
    $arResult["ITEMS"] = $this->getOffices($arParams);
}
$this->includeComponentTemplate();
?>