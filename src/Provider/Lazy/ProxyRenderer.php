<?php


namespace Brunt\Provider\Lazy;


use Brunt\Provider\Lazy\T\ProxyTrait;
use Brunt\Reflection\CR\CRClass;
use Brunt\Reflection\CR\CRField;
use Brunt\Reflection\CR\CRMethod;
use Brunt\Reflection\CR\CRParam;
use Brunt\Reflection\CR\CRRenderer;
use Brunt\Reflection\Reflector;

class ProxyRenderer extends CRRenderer
{
    /**
     * @var CRClass
     */
    private $class;
    /**
     * @var Reflector
     */
    private $reflector;
    /**
     * @var string
     */
    private $proxyClassName;

    /**
     * DebugRenderer constructor.
     * @param Reflector $reflector
     * @param string $proxyClassName
     */
    public function __construct(CRClass $class, string $proxyClassName)
    {
        $this->class = $class;


     //   $this->reflector = $reflector;

        $this->proxyClassName = $proxyClassName;
    }

    /**
     * @return string
     */
    public function render()
    {
//        print_r('ProxyRenderer::render');
        return $this->renderClass($this->class, 0, '    ');
    }

    protected function renderClass(CRClass $class, $depth = 0, $indent = " ")
    {
        return
            'namespace Brunt\ProxyObject; use Brunt\Provider\Lazy\T\ProxyTrait; class ' . $this->proxyClassName .
            ' extends \\' . $class->getClassName() .
            $this->braces(
                '',
                $this->renderTraits($class, $depth + 1, $indent),
                //  $this->renderFields($class->getMethods(), $depth + 1, $indent),
                $this->renderMethods($class->getMethods(), $depth + 1, $indent),
                ''
            );
    }

    /**
     * @return string
     */
    protected function getInstanceVariableName()
    {
        return 'getInstance()';
    }

    protected function renderTraits(CRClass $class, $depth = 0, $indent = " ")
    {
        $traits = [ 'ProxyTrait'  ];

        return implode($this->statementSeperator(), array_map(function ($trait) {
            return 'use ' . $trait . ';';
        }, $traits));
    }

    /**
     * @param CRField[] $fields
     * @param int $depth
     * @param string $indent
     * @return string
     */
    protected function renderFields(array $fields, $depth = 0, $indent = " ")
    {
        return '';
    }


    /**
     * @param CRField $field
     * @param int $depth
     * @param string $indent
     * @return string
     */
    protected function renderField(CRField $field, $depth = 0, $indent = " ")
    {
        return '';
    }

    /**
     * @param CRMethod[] $methods
     * @param int $depth
     * @param string $indent
     * @return string
     */
    protected function renderMethods(array $methods, $depth = 0, $indent = " ")
    {


        return array_map(
            function (CRMethod $method) use ($depth, $indent) {
                return $this->renderMethod($method, $depth, $indent);
            },
            array_filter($methods, function (CRMethod $method) {
                return
                    $method->isPublic() &&
                    !$method->isStatic() &&
                    !$this->isExcludedFromRenderingMethod($method);
            })

        );
    }

    /**
     * @param CRMethod $method
     * @param int $depth
     * @param string $indent
     * @return string
     */
    protected function renderMethod(CRMethod $method, $depth = 0, $indent = " ")
    {
        return            //function and name
            'public function ' . $method->getName() .
            //params
            $this->parentheses(
                $this->renderParams($method->getParams(), $depth, $indent)
            ) .
            //body
            $this->braces(
                $this->renderMethodBody($method, $depth + 1, $indent)
            );
    }

    protected function renderMethodBody(CRMethod $method, $depth = 0, $indent = " ")
    {
        return $this->renderDepth($depth, $indent) . 'return $' . "this->" . $this->getInstanceVariableName() . "->" . $method->getName() . '(... func_get_args());';
    }


    /**
     * @param CRParam[] $params
     * @param int $depth
     * @param string $indent
     * @return string
     */
    protected function renderParams(array $params, $depth = 0, $indent = " ")
    {
        return implode(', ',
            array_map(
                function (CRParam $param) {
                    return $this->renderParam($param);
                }, $params
            )
        );
    }

    /**
     * @param CRParam $param
     * @param int $depth
     * @param string $indent
     * @return string
     */
    protected function renderParam(CRParam $param, $depth = 0, $indent = " ")
    {



        $s = "";
        if ($param->hasType()) {

            $s .= ($param->isBuiltin()?'':'\\').$param->getType() . ' ';
        }
          if ($param->isPassedByReference()) {
              $s .= '&';
          }


        $s .= "$" . $param->getName();
        if ($param->isOptional()) {
//REALLY?
//            if ($param->getParameter()->isDefaultValueAvailable()) {
//                $default = $this->renderParamDefaultValue($param);
//            } else {
            $default = 'null';
//            }
            $s .= " = " . $default;
        }
        return $s;
    }

    /**
     * @param $depth
     * @param string $indent
     * @return string
     */
    private function renderDepth($depth, $indent = " ")
    {
        return implode('', array_fill(0, $depth, $indent));
    }


    /**
     * unused ... but could be useful
     * @param string[] $modifiers
     * @return string
     */
    private function renderModifier(array $modifiers)
    {
        return implode(
            '',
            array_map(
                function ($mod) {
                    return $mod . ' ';
                },
                $modifiers
            )
        );
    }

    private function getOverriddenMagicMethodsByProxyTraitNames()
    {
        return ["__construct", "__call", "__get", "__set", "__isset", "__unset", "__toString", "__invoke"];
    }

    /*
     * methods
     */
    private function getIgnoredMagicMethodNames()
    {
        return ["__callStatic", "__destruct", "__sleep", "__wakeup", "__set_state", "__clone"];
    }

    /**
     * @return array
     */
    private function getMagicMethodNames():array
    {
        return ["__construct", "__destruct", "__call", "__callStatic", "__get", "__set", "__isset", "__unset", "__sleep", "__wakeup", "__toString", "__invoke", "__set_state", "__clone", "__debugInfo"];
    }


    private function isExcludedFromRenderingMethod(CRMethod $method)
    {
        return $this->isExcludedFromRenderingMethodName($method->getName());
    }

    private function isExcludedFromRenderingMethodName($methodName)
    {
        return
            in_array($methodName, $this->getOverriddenMagicMethodsByProxyTraitNames()) ||
            in_array($methodName, $this->getIgnoredMagicMethodNames());
    }


    /**
     * @param string $functionName
     * @return bool
     */
    private function isMagicMethodName(string $functionName):bool
    {
        return in_array($functionName, $this->getMagicMethodNames());
    }

    /**
     * @param string $functionName
     * @return bool
     */
    private function isMagicMethod(CRMethod $method):bool
    {
        return $this->isMagicMethodName($method->getMethodName());
    }


    //---------------------------------------------------------------------

    protected function parentheses(... $content)
    {

        return $this->beforeParenthesesOpening() . '(' . $this->afterParenthesesOpening() . $this->serializeContent($content) . $this->beforeParenthesesOpening() . ')' . $this->beforeParenthesesOpening();
    }

    protected function beforeParenthesesOpening()
    {
        return '';
    }

    protected function afterParenthesesOpening()
    {
        return '';
    }

    protected function beforeParenthesesClosing()
    {
        return '';
    }

    protected function afterParenthesesClosing()
    {
        return '';
    }

    //---------------------------

    protected function serializeContent( $content)
    {
        $v = implode($this->blockSeperator(), array_map(function ($item) {
            if (is_array($item)) {

                return $this->serializeContent($item);
            } else {
                return $item;
            }
        }, $content));

        return $v;
    }


    protected function braces(... $content)
    {

        return $this->beforeBracesOpening() . '{' . $this->afterBracesOpening() . $this->serializeContent($content) . $this->beforeBracesClosing() . '}' . $this->afterBracesClosing();
    }

    protected function beforeBracesOpening()
    {
        return '';
    }

    protected function afterBracesOpening()
    {
        return '';
    }

    protected function beforeBracesClosing()
    {
        return '';
    }

    protected function afterBracesClosing()
    {
        return '';
    }


    //---------------------------

    protected function chevrons(... $content)
    {
        return $this->beforeChevronsOpening() . '<' . $this->afterChevronsOpening() . $this->serializeContent($content) . $this->beforeChevronsClosing() . '>' . $this->afterChevronsClosing();
    }

    protected function beforeChevronsOpening()
    {
        return '';
    }

    protected function afterChevronsOpening()
    {
        return '';
    }

    protected function beforeChevronsClosing()
    {
        return '';
    }

    protected function afterChevronsClosing()
    {
        return '';
    }

    //---------------------------

    protected function brackets(... $content)
    {
        return $this->beforeBracketsOpening() . '[' . $this->afterBracketsOpening() . $this->serializeContent($content) . $this->beforeBracketsClosing() . ']' . $this->afterBracketsClosing();
    }

    protected function beforeBracketsOpening()
    {
        return '';
    }

    protected function afterBracketsOpening()
    {
        return '';
    }


    protected function beforeBracketsClosing()
    {
        return '';
    }

    protected function afterBracketsClosing()
    {
        return '';
    }


    protected function traitSeperator()
    {
        return '';
    }

    protected function blockSeperator()
    {
        return PHP_EOL;
    }

    protected function statementSeperator()
    {
        return PHP_EOL;
    }

    private function renderParamDefaultValue(CRParam $param)
    {
        return 'null';
    }

}