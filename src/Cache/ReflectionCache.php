<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 12.06.16
 * Time: 22:53
 */

namespace Brunt\Cache;


use Brunt\Reflection\Reflector;

class ReflectionCache
{

    const PATH = 'tmp/cache/reflector';
    private static $instance;
    private static $config;

    private function __construct(Filesystem $filesystem)
    {
        $this->fs = $filesystem;
        $this->cacheDirectoryExistsOrCreate();
        $this->loadConfigOrCreate();
    }

    public function cacheDirectoryExistsOrCreate()
    {
        if (!$this->fs->exists(ReflectionCache::PATH)) {
            $this->fs->mkdir(ReflectionCache::PATH);
        }
    }

    private function loadConfigOrCreate()
    {
        if (self::$config) {
            return;
        }
        if ($this->fs->exists(self::PATH . '/config.php')) {
            self::$config = (include(self::PATH . '/config.php'));
        } else {
            self::$config = [];
        }
        //   print_r($this->config);
    }

    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new self(Filesystem::init());
        }
        return self::$instance;
    }

    public function isCached($className)
    {
        return isset(self::$config[$className]);
    }

    public function read($className)
    {
        return self::$config[$className];
    }

    public function write(Reflector $reflector)
    {
        $data = $reflector->toArray();
        $hash = hash_file('sha256', $data['getFileName']);
        $fileSize = filesize($data['getFileName']);

        self::$config = [
                $data['getClassName'] => $data +
                    [
                        'fileSize' => $fileSize,
                        'fileHash' => $hash,]
            ] + self::$config;

        $this->fs->dumpFile(self::PATH . '/config.php',
            "<?php return " . $this->renderArray(self::$config) . "; " . PHP_EOL .
            '/*' . PHP_EOL .
            print_r(self::$config, true) . PHP_EOL .
            '*/' . PHP_EOL
        );
    }

    private function renderArray($config, $depth = 0)
    {

        $indent = function ($depth = 0) {
            return implode('', array_fill(0, $depth, ' '));
        };

        $str =  PHP_EOL.$indent($depth) .'[' . PHP_EOL;
        foreach ($config as $key => $value) {
            if (is_string($key)) {
                $str .= $indent($depth+1) . "'" . $key . "'=>";
            } else {
                $str .= $indent($depth+1);
            }
            if (is_string($value)) {
                $str .= "'" . $value . "'";
            } else if (is_array($value)) {
                if(empty($value)){
                    $str .= '[]';
                }else{
                    $str .= $this->renderArray($value, $depth + 2);
                }
            } else if (is_bool($value)) {
                $str .= $value ? 'true' : 'false';
            } else {
                $str .= $value;
            }
            $str .= ',' . PHP_EOL;
        }
        $str .= $indent($depth) .']';
        return $str;
    }
}