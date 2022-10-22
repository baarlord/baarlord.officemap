<?php

namespace {
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE & ~E_DEPRECATED);

    $docRoot = getenv('TEST_BX_ROOT');
    if (empty($docRoot)) {
        $docRoot = dirname(__DIR__, 4);
    }

    define('B_PROLOG_INCLUDED', true);
    define('SITE_ID', getenv('SITE_ID'));
    define('LANGUAGE_ID', getenv('LANGUAGE_ID'));

    $_SERVER['DOCUMENT_ROOT'] = $docRoot;
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/bx_root.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/lib/loader.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/autoload.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/lib/exception.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/mysql/sqlwhere.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/component.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/component_template.php');
}

namespace Bitrix\Main\Localization {
    class EO_Culture
    {
    }

    final class Loc
    {
        public static function loadMessages($filePath)
        {
        }

        public static function getMessage($code, $replace = null, $language = null)
        {
            return $code;
        }
    }
}

namespace _test_ {

    use Bitrix\Main\Application;
    use Bitrix\Main\Context;
    use Bitrix\Main\Context\Culture;
    use Bitrix\Main\Data\ConnectionPool;
    use Bitrix\Main\Data\ManagedCache;
    use function getenv;

    class CacheStub extends ManagedCache
    {
        public function __construct()
        {
        }

        protected static function getDbType()
        {
            return false;
        }

        public function read($ttl, $uniqueId, $tableId = false)
        {
            return false;
        }

        public function getImmediate($ttl, $uniqueId, $tableId = false)
        {
            return false;
        }

        public function get($uniqueId)
        {
            return false;
        }

        public function set($uniqueId, $val)
        {
        }

        public function setImmediate($uniqueId, $val)
        {
        }

        public function clean($uniqueId, $tableId = false)
        {
        }

        public function cleanDir($tableId)
        {
        }

        public function cleanAll()
        {
        }

        public static function finalize()
        {
        }

        public function getCompCachePath($relativePath)
        {
            return false;
        }
    }

    class AppStub extends Application
    {
        public function __construct()
        {
            $this->managedCache = new CacheStub();
            $this->connectionPool = new class extends ConnectionPool {
                protected function getConnectionParameters($name)
                {
                    switch ($name) {
                        case ConnectionPool::DEFAULT_CONNECTION_NAME:
                            return [
                                'host' => getenv('DB_HOST'),
                                'database' => getenv('DB_NAME'),
                                'login' => getenv('DB_USER'),
                                'password' => getenv('DB_PASSWD'),
                                'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
                                'options' => 0,
                            ];
                        default:
                            return null;
                    }
                }
            };
        }

        protected function initializeContext(array $params)
        {
            $culture = new class() extends Culture {
                public function getDateTimeFormat()
                {
                    return "DD.MM.YYYY HH:MI:SS";
                }

                public function getDateFormat()
                {
                    return "YYYY-MM-DD";
                }

                public function getNameFormat()
                {
                    return "#NAME# #LAST_NAME#";
                }
            };

            $this->context = new Context($this);
            $this->context->setCulture($culture);
        }

        public function start()
        {
        }
    }

    AppStub::getInstance()->initializeExtendedKernel([]);
}
