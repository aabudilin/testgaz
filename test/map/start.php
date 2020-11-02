<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Создаем структуру инфоблоков");

require($_SERVER["DOCUMENT_ROOT"]."/local/src/Gazprom/Test/CreateIblocks.php");

use Gazprom\Test\CreateIblocks,
	Bitrix\Main\Loader;

Loader::includeModule("iblock");

$start = new CreateIblocks();

?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>