<?php
namespace My\Component;

use Bitrix\Main\Context;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Bitrix\Main\UserTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\Elements\ElementPositionsTable; 
use Bitrix\Iblock\Elements\ElementCarParkTable;          
use Bitrix\Iblock\Elements\ElementDriversTable;         
use Bitrix\Iblock\Elements\ElementCarModelsTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;     


class Reservation extends \CBitrixComponent
{
	public function executeComponent()
	{
		global $USER;

        if (!$USER->IsAuthorized()) {
            $this->arResult['ERROR'] = 'Пожалуйста, авторизуйтесь на сайте.';
            return $this->arResult;
        }
		
		if (!Loader::includeModule('iblock') || !Loader::includeModule('highloadblock')) {
            return; 
        }
 
        $dates = $this->parseAndValidateDates();
		
        if (empty($dates)) {
            $this->arResult['ERROR'] = 'Укажите корректные дату начала и окончания поездки.';
            return $this->arResult;
        }
		
        [$dateFrom, $dateTo] = $dates;

        $userId = (int)$USER->GetID();
        $allowedCategories = $this->getUserComfortCategories($userId);

		$busyCarIds = $this->getBusyCarIds($dateFrom, $dateTo);

        $this->arResult['CARS'] = $this->getAvailableCars($allowedCategories, $busyCarIds);

        return $this->arResult;
	}

	private function parseAndValidateDates(): array
	{
		$request = Context::getCurrent()->getRequest();
		$rawFrom = $request->getQuery('date_from');
		$rawTo = $request->getQuery('date_to');

		if( empty($rawFrom) || empty($rawTo) ) {
			return [];
		} 

		try{
			$dateFrom = DateTime::createFromUserTime($rawFrom);
			$dateTo = DateTime::createFromUserTime($rawTo);

			if ($dateFrom < $dateTo) {
				return [$dateFrom, $dateTo];
			}
		} catch (\Exception $e) {
			return [];
		}

		return [];
	}

	private function getUserComfortCategories(int $userId): array
	{

		$rows = \Bitrix\Main\UserTable::getList([
			'select' => [ 
				'user_id' => 'ID', 
				'position_name' => 'positions.NAME', 
				'comfort_level' => 'positions.COMFORT_LEVEL.VALUE' 
			], 
			'filter' => [
				'=ID' => $userId
			],
			'runtime' => [
				'POSITIONS' => (new Reference(
					'POSITIONS',
					ElementPositionsTable::class,
					Join::on('this.UF_POSITION', 'ref.ID')
				))->configureJoinType(Join::TYPE_LEFT)
			]
		])->fetchAll();

		$categories = [];

		foreach ($rows as $row) {
		
			if (!empty($row['comfort_level'])) {
				$categories[] = $row['comfort_level']; 
			}
		}

		return array_unique($categories);

	}

    private function getBusyCarIds(DateTime $dateFrom, DateTime $dateTo): array
    {

		if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
			return [];
		} 

		$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList([
			'filter' => ['=NAME' => 'CarReservation']
		])->fetch();

		if (!$hlblock) {
			return []; 
		}

    $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);

    $reservationTableClass = $entity->getDataClass();

		$strTo = $dateTo->toString();
		$strFrom = $dateFrom->toString();
  
		$rows = $reservationTableClass::getList([
			'select' => ['uf_car'],
			'filter' => [
				'<UF_DATE_FROM' => $strTo,
				'>UF_DATE_TO' => $strFrom,
			]
		])->fetchAll();
		
		$busyIds = [];

		foreach ($rows as $row) {
			if (!empty($row['UF_CAR'])) {
				$busyIds[] = (int)$row['UF_CAR'];
			}
		}

		return array_unique($busyIds);

	}
	
    private function getAvailableCars(array $allowedCategories, array $busyCarIds): array
    {
        if (empty($allowedCategories)) {
            return [];
        }

        $filter = [
            '=ACTIVE' => 'Y',
            '=MODEL.ELEMENT.COMFORT_LEVEL.VALUE' => $allowedCategories
        ];

        if (!empty($busyCarIds)) {
            $filter['!=ID'] = $busyCarIds;
        }

        return ElementCarParkTable::getList([
            'select' => [
                'car_id' => 'ID',
                'car_name' => 'NAME',
                'nomer' => 'GOSNOMER.VALUE',
                'model_name' => 'MODEL.ELEMENT.NAME',
                'driver_name' => 'DRIVER.ELEMENT.NAME',
                'comfort_level' => 'MODEL.ELEMENT.COMFORT_LEVEL.VALUE'
            ],
            'filter' => $filter
        ])->fetchAll();
    }

}







