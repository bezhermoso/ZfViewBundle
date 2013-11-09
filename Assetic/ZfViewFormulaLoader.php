<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bzl\Bundle\ZfViewBundle\Assetic;


use Assetic\Factory\AssetFactory;
use Assetic\Factory\Loader\BasePhpFormulaLoader;
use Assetic\Factory\Loader\FormulaLoaderInterface;
use Assetic\Factory\Resource\ResourceInterface;

/**
 * Class ZfViewFormulaLoader
 *
 * Assetic formulae loader for ZfViewBundle.
 * Heavily based on BasePhpFormulaLoader, however changes was made in processCall to make $this calls work.
 *
 * @todo Implement formula loader for ZfViewBundle
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\Assetic
 */
class ZfViewFormulaLoader extends BasePhpFormulaLoader implements FormulaLoaderInterface
{

    protected $factory;
    protected $prototypes;

    /**
     * Loads formulae from a resource.
     *
     * Formulae should be loaded the same regardless of the current debug
     * mode. Debug considerations should happen downstream.
     *
     * @param ResourceInterface $resource A resource
     *
     * @throws \LogicException
     * @return array An array of formulae
     */
    public function load(ResourceInterface $resource)
    {
        if (!$nbProtos = count($this->prototypes)) {
            throw new \LogicException('There are no prototypes registered.');
        }

        $buffers = array_fill(0, $nbProtos, '');
        $bufferLevels = array_fill(0, $nbProtos, 0);
        $buffersInWildcard = array();

        $tokens = token_get_all($resource->getContent());
        $calls = array();

        while ($token = array_shift($tokens)) {
            $current = self::tokenToString($token);
            // loop through each prototype (by reference)
            foreach (array_keys($this->prototypes) as $i) {
                $prototype =& $this->prototypes[$i][0];
                $options = $this->prototypes[$i][1];
                $buffer =& $buffers[$i];
                $level =& $bufferLevels[$i];

                if (isset($buffersInWildcard[$i])) {
                    switch ($current) {
                        case '(': ++$level; break;
                        case ')': --$level; break;
                    }

                    $buffer .= $current;

                    if (!$level) {
                        $calls[] = array($buffer.';', $options);
                        $buffer = '';
                        unset($buffersInWildcard[$i]);
                    }
                } elseif ($current == self::tokenToString(current($prototype))) {
                    $buffer .= $current;
                    if ('*' == self::tokenToString(next($prototype))) {
                        $buffersInWildcard[$i] = true;
                        ++$level;
                    }
                } else {
                    reset($prototype);
                    unset($buffersInWildcard[$i]);
                    $buffer = '';
                }
            }
        }

        $formulae = array();

        foreach ($calls as $call) {
            $formulae += call_user_func_array(array($this, 'processCall'), $call);
        }

        return $formulae;
    }

    /**
     * Returns an array of prototypical calls and options.
     *
     * @return array Prototypes and options
     */
    protected function registerPrototypes()
    {
        return array(
            '$this->assetic()->javascripts(*)' => array('output' => 'js/*.js'),
            '$this->assetic()->stylesheets(*)' => array('output' => 'css/*.css'),
            '$this->assetic()->image(*)' => array('output' => 'images/*', 'single' => true),
        );
    }

    private function processCall($call, array $protoOptions = array())
    {

        $tmp = tempnam(sys_get_temp_dir(), 'assetic');
        file_put_contents($tmp, implode("\n", array(
            '<?php',
            $this->setupCode($call),
            'echo serialize($_call);',
        )));
        $args = unserialize(shell_exec('php '.escapeshellarg($tmp)));
        unlink($tmp);

        $inputs  = isset($args[0]) ? self::argumentToArray($args[0]) : array();
        $filters = isset($args[1]) ? self::argumentToArray($args[1]) : array();
        $options = isset($args[2]) ? $args[2] : array();

        if (!isset($options['debug'])) {
            $options['debug'] = $this->factory->isDebug();
        }

        if (!is_array($options)) {
            throw new \RuntimeException('The third argument must be omitted, null or an array.');
        }

        // apply the prototype options
        $options += $protoOptions;

        if (!isset($options['name'])) {
            $options['name'] = $this->factory->generateAssetName($inputs, $filters, $options);
        }

        return array($options['name'] => array($inputs, $filters, $options));
    }

    /**
     * Returns setup code for the reflection scriptlet.
     *
     * @param $call
     * @return string Some PHP setup code
     */
    protected function setupCode($call)
    {
        return <<<EOF
class Helper
{
    public function assets()
    {
        global \$_call;
        \$_call = func_get_args();
    }

    public function javascripts()
    {
        global \$_call;
        \$_call = func_get_args();
    }

    public function stylesheets()
    {
        global \$_call;
        \$_call = func_get_args();
    }

    public function image()
    {
        global \$_call;
        \$_call = func_get_args();
    }
}

class Assetic
{
    public function __construct(Helper \$helper)
    {
        \$this->helper = \$helper;
    }

    public function assetic()
    {
        return \$this->helper;
    }

    public function call()
    {
        $call
    }
}

\$assetic = new Assetic(new Helper());
\$assetic->call();

EOF;
    }

    /**
     * Not used in this class.
     *
     * Returns setup code for the reflection scriptlet.
     *
     * @return string Some PHP setup code
     */
    protected function registerSetupCode()
    {
        return '';
    }
}