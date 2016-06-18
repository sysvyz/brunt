<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 12.06.16
 * Time: 16:41
 */

namespace Brunt\Cache;


use Brunt\Reflection\Reflector;

class ProxyCache
{
    const PATH = 'tmp/cache/lazy';
    const FILENAME = 'config.php';
    public static $EAGER = true;
    public static $DEV = true;


    private static $instance = null;
    private static $config = null;
    private $filesystem;

    /**
     * ProxyChache constructor.
     */
    private function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->cacheDirectoryExistsOrCreate();
        $this->loadConfigOrCreate();
    }

    public function cacheDirectoryExistsOrCreate()
    {
        if (!$this->filesystem->exists(self::PATH)) {
            $this->filesystem->mkdir(self::PATH);
        }
    }

    private function loadConfigOrCreate()
    {
        if (self::$config) {
            return;
        }
        if ($this->filesystem->exists(self::PATH . '/' . self::FILENAME)) {
            self::$config = (include(self::PATH . '/' . self::FILENAME));
        } else {
            self::$config = [];
        }
        //     print_r(self::$config);
    }

    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new self(Filesystem::init());
        }
        return self::$instance;
    }

    public function write($class, Reflector $reflector, $proxyClassName)
    {

//       print_r('write' . PHP_EOL);
       // $e = $this->fs->exists(self::PATH);

        $this->filesystem->dumpFile(self::PATH . '/' . $proxyClassName . '.php', '<?php ' . $class);
        //CONFIG
        $originalFileName = $reflector->getFileName();
        $hash = hash_file('sha256', $originalFileName);
        $fileSize = filesize($originalFileName);
        $className = $reflector->getClassName();
        $data = [
            $className => [
                'file' => $originalFileName,
                'fileSize' => $fileSize,
                'fileHash' => $hash,
                'className' => $className,
                'proxyClassName' => $proxyClassName
            ]
        ];
        self::$config = $data + self::$config;


        $this->filesystem->dumpFile(self::PATH . '/' . self::FILENAME,
            "<?php return " . $this->renderArray(self::$config) . "; " . PHP_EOL .
            '/*' . PHP_EOL .
            print_r(self::$config, true) . PHP_EOL .
            '*/' . PHP_EOL
        );
    }

    private function renderArray($config)
    {
        $str = '[';
        foreach ($config as $key => $value) {
            if (is_string($key)) {
                $str .= "'" . $key . "'=>";
            }
            if (is_string($value)) {
                $str .= "'" . $value . "'";
            } else if (is_array($value)) {
                $str .= $this->renderArray($value);
            } else {
                $str .= $value;
            }
            $str .= ',';
        }

        $str .= ']';
        return $str;
    }

    public function read(Reflector $reflector, $proxyClassName)
    {

//        print_r('read' . PHP_EOL);

        if (!isset(self::$config[$reflector->getClassName()])) {
            print_r('read !isset' . PHP_EOL);
            return false;
        }
        $cfg = self::$config[$reflector->getClassName()];
        $file = self::PATH . '/' . $cfg['proxyClassName'] . '.php';
        if ($this->filesystem->exists($file)) {

            include($file);
            return true;
        }


        return false;
    }
}