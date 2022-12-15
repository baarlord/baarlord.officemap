<?php
defined('B_PROLOG_INCLUDED') || die;

use Baarlord\OfficeMap\Entity\Office;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Error as BitrixError;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class boOfficesDetailComponent extends CBitrixComponent implements Errorable
{
    private ErrorCollection $errors;
    private EntityRepository $repository;

    public function __construct($component = null)
    {
        parent::__construct($component);
        $this->errors = new ErrorCollection();
        /** @var EntityManager $entityManager */
        $entityManager = ServiceLocator::getInstance()->get('baarlord.officemap.doctrineEntityManager');
        $this->repository = $entityManager->getRepository(Office::class);
        $t = 3;
    }

    public function getErrors(): array
    {
        return $this->errors->toArray();
    }

    public function getErrorByCode($code): ?BitrixError
    {
        return $this->errors->getErrorByCode($code);
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams =  parent::onPrepareComponentParams($arParams);
        $arParams['ELEMENT_ID'] = $arParams['ELEMENT_ID'] ? (int)$arParams['ELEMENT_ID'] : 0;
        return $arParams;
    }

    public function executeComponent()
    {
        if (
            !Loader::includeModule('baarlord.officemap')
        ) {
            ShowError('Can\'t include required modules');
            return;
        }
        $element = $this->getElementById();
        if ($this->errors->count() > 0) {
            ShowError(implode('<br>', $this->getErrors()));
            return;
        }
        $this->includeComponentTemplate();
    }

    private function getElementById(): Office
    {
        if ($this->arParams['ELEMENT_ID'] === 0) {
            return new Office(
                0,
                Loc::getMessage('NEW_ELEMENT_TITLE'),
                'Y',
                CUtil::translit(Loc::getMessage('NEW_ELEMENT_TITLE'), LANG),
                '',
                null,
                500
            );
        }
        $element = $this->repository->find($this->arParams['ELEMENT_ID']);
        if (empty($element)) {
            $this->errors->setError(
                new BitrixError(
                    Loc::getMessage('ERROR_CANT_FOUND_BY_ID', ['#ID#' => $this->arParams['ELEMENT_ID']])
                )
            );
        }
        return $element;
    }
}
