<?php
defined('B_PROLOG_INCLUDED') || die;

use Baarlord\OfficeMap\Entity\Office;
use Baarlord\OfficeMap\Service\BaseModelService;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\Response\Json;
use Bitrix\Main\Engine\UrlManager;
use Bitrix\Main\Error as BitrixError;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectException;
use Bitrix\Main\ObjectNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Psr\Container\NotFoundExceptionInterface;

class boOfficesDetailComponent extends CBitrixComponent implements Errorable, Controllerable
{
    private ErrorCollection $errors;
    private EntityRepository $repository;
    private BaseModelService $service;

    /**
     * @throws LoaderException
     * @throws NotFoundExceptionInterface
     * @throws ObjectNotFoundException
     * @throws ObjectException
     */
    public function __construct($component = null)
    {
        parent::__construct($component);
        $this->errors = new ErrorCollection();
        if (
            !Loader::includeModule('baarlord.officemap')
        ) {
            throw new ObjectException(new BitrixError('Can\'t include required modules'));
        }
        /** @var EntityManager $entityManager */
        $entityManager = ServiceLocator::getInstance()->get('baarlord.officemap.doctrineEntityManager');
        $this->repository = $entityManager->getRepository(Office::class);
        $this->service = ServiceLocator::getInstance()->get('baarlord.officemap.service.OfficeService');
    }

    public function configureActions(): array
    {
        return [];
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
        $arParams = parent::onPrepareComponentParams($arParams);
        $arParams['ELEMENT_ID'] = $arParams['ELEMENT_ID'] ? (int)$arParams['ELEMENT_ID'] : 0;
        return $arParams;
    }

    public function executeComponent()
    {
        $office = $this->getElementById();
        if ($this->errors->count() > 0) {
            ShowError(implode('<br>', $this->getErrors()));
            return;
        }
        $this->arResult['ENTITY_DATA'] = $this->getEntityData($office);
        $this->arResult['SERVICE_URL'] = $this->getServiceUrl();

        $this->includeComponentTemplate();
    }

    private function getElementById(): ?Office
    {
        if ($this->arParams['ELEMENT_ID'] === 0) {
            return new Office(
                0,
                Loc::getMessage('NEW_ELEMENT_TITLE'),
                'Y',
                CUtil::translit(Loc::getMessage('NEW_ELEMENT_TITLE'), LANGUAGE_ID),
                '',
                null,
                500
            );
        }
        $office = $this->repository->find($this->arParams['ELEMENT_ID']);
        if (empty($office)) {
            $this->errors->setError(
                new BitrixError(
                    Loc::getMessage('ERROR_CANT_FOUND_BY_ID', ['#ID#' => $this->arParams['ELEMENT_ID']])
                )
            );
        }
        return $office;
    }

    private function getEntityData(Office $office): array
    {
        $data = [];
        foreach ($office->toArray() as $field => $value) {
            if ($field === 'FILE') {
                $value = [];
            }
            $data[$field] = $value;
        }
        return $data;
    }

    private function getServiceUrl(): string
    {
        return UrlManager::getInstance()->createByBitrixComponent($this, 'compatible', [
            'entityId' => 0,
            'sessid' => bitrix_sessid(),
        ]);
    }

    public function compatibleAction(int $entityId): ?Json
    {
        $data = [];
        if ($this->request->getPost('ACTION') === 'RENDER_IMAGE_INPUT') {
            $this->renderImageInput();
        }
        if ($this->request->getPost('ACTION') === 'SAVE') {
            $data = $this->saveEntity();
        }
        return new Json($data);
    }

    protected function renderImageInput(): void
    {
        global $APPLICATION;
        $fieldName = $this->request->getPost('FIELD_NAME') ?
            $this->request->getPost('FIELD_NAME') : '';
        $value = [];
        Header('Content-Type: text/html; charset=' . LANG_CHARSET);
        $APPLICATION->ShowAjaxHead();
        $APPLICATION->IncludeComponent(
            'bitrix:main.file.input',
            '',
            [
                'MODULE_ID' => 'baarlord.officemap',
                'MAX_FILE_SIZE' => CUtil::Unformat(ini_get('upload_max_filesize')),
                'SHOW_AVATAR_EDITOR' => 'Y',
                'MULTIPLE' => 'N',
                'ALLOW_UPLOAD' => $this->request->getPost('ALLOW_UPLOAD'),
                'CONTROL_ID' => mb_strtolower($fieldName) . '_uploader',
                'INPUT_NAME' => $fieldName . '[]',
                'INPUT_NAME_UNSAVED' => $fieldName . '_tmp',
                'INPUT_VALUE' => $value
            ],
        );
        require_once(dirname(__DIR__, 3) . '/modules/main/include/epilog_after.php');
        die();
    }

    protected function saveEntity(): array
    {
        $entity = new Office(
            $this->request->getPost('ID') ?? 0,
                $this->request->getPost('NAME'),
                $this->request->getPost('ACTIVE'),
                $this->request->getPost('CODE'),
                $this->request->getPost('FLOOR'),
                $this->request->getPost('FILE'),
                $this->request->getPost('SORT')
        );
        $this->service->save($entity);
        return [];
    }
}
