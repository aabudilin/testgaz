<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$this->addExternalJS("https://api-maps.yandex.ru/2.1/?lang=ru-RU&apikey=".$arResult['KEY']);
?>

<div id="fullmap" class="full-map"></div>

<script>

let data = {
"type":"FeatureCollection",
"features":[
<?foreach($arResult["ITEMS"] as $key => $arItem):?>
	<?
		$contentBaloon = '';
		if ($arItem['PROP']['EMAIL']) {
			$contentBaloon .= '<p>'.$arItem['PROP']['EMAIL'].'</p>';
		}
		if ($arItem['PROP']['PHONE']) {
			$contentBaloon .= '<p>'.$arItem['PROP']['PHONE'].'</p>';
		}
		if ($arItem['PROP']['CITY']) {
			$contentBaloon .= '<p>'.$arItem['PROP']['CITY'].'</p>';
		}
	$contentBaloon .= "<p><a href='/test/map/office/?ID=".$arItem["ID"]."' target='_blanck'>Подробнее</a></p>";
	?>
	{"type": "Feature", "id":<?=$key?>, "geometry": {"type": "Point", "coordinates": [<?=$arItem['PROP']['COORD']?>]}, "properties": {"balloonContentHeader": "<?=htmlspecialchars($arItem['NAME'])?>", "balloonContentBody": "<?=$contentBaloon?>", "balloonContentFooter": "", "clusterCaption": "<strong><?=htmlspecialchars($arItem['NAME'])?></strong>", "hintContent": "<strong><?=htmlspecialchars($arItem['NAME'])?></strong>"}},
<?endforeach?>
	]
}

ymaps.ready(init);

function init () {
    var myMap = new ymaps.Map('fullmap', {
            center: [55.76, 37.64],
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        }),
        objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32,
            clusterDisableClickZoom: true
        });

    // Чтобы задать опции одиночным объектам и кластерам,
    // обратимся к дочерним коллекциям ObjectManager.
    objectManager.objects.options.set('preset', 'islands#redDotIcon');
    objectManager.clusters.options.set('preset', 'islands#redClusterIcons');
    myMap.geoObjects.add(objectManager);

    objectManager.add(data);

     myMap.setBounds(myMap.geoObjects.getBounds());

}
</script>
