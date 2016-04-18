<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:28
 */

namespace Prescription\Provider;
use Prescription\Exception\CircularDependencyException;
use Prescription\Exception\InjectableException;
use Prescription\InjectableInterface;
use Prescription\Injector;

class ClassProvider implements ProviderInterface
{
    /**
     * @var Reflector
     */
    private $reflector;
    /**
     * @var bool
     */
    private $singleton;
    private $instance = null;
    /**
     * @var string
     */
    private $class;

    private $counter = 0;
    private $counter2 = 0;


    /**
     * ClassProvider constructor.
     * @param string $class
     * @param bool $singleton
     */
    public function __construct($class, $singleton = true)
    {
        $this->reflector = new Reflector($class);
        $this->singleton = $singleton;
        $this->class = $class;

        //todo disable for production?
        self::validate($this->reflector);
    }

    private static function validate(Reflector $reflector, $path = [])
    {
        //get class name
        $className = $reflector->getClassName();
        if ($className == Injector::class)
            return;

        if (in_array($className, $path))
            throw new CircularDependencyException ($className . ' must not depend on it self');


        array_push($path, $reflector->getClassName());
        foreach ($reflector->getDependencies() as $dependency) {
            if (!$dependency['isNative']) {
                $nextReflector = new Reflector($dependency['typeString']);
                self::validate($nextReflector, $path);
            }
        }

    }


    public static function init($class, $singleton = true)
    {
        return new static($class, $singleton);
    }

    function __invoke(Injector $injector)
    {


        //return singleton if instance is set and singleton mode
        if ($this->singleton && $this->instance !== null) {
            return $this->instance;
        }


        //get class name
        $className = $this->reflector->getClassName();


        //build new injector for instance
        $isInjectable = $this->reflector->getReflectionClass()->implementsInterface(InjectableInterface::class);

        if (!$isInjectable) {
            throw new InjectableException ($this->reflector->getClassName() . ' must implement ' . InjectableInterface::class);
        }

        /** @var InjectableInterface $className */
        $providers = $className::_DI_PROVIDERS();

        $childInjector = $injector->getChild($providers);

        //get dependencies
        $dependencies = $this->reflector->getDependencies();

        //recursive build dependencies
        $params = [];

        foreach ($dependencies as $dependency) {

            $token = ($dependency['isNative'])
                ? $dependency['native']
                : $dependency['typeString'];


            $params[] = $childInjector->get($token);


        }

        //build instance
        $this->instance = new $className(...$params);

        return $this->instance;
    }

}
