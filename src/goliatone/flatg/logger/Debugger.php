<?php namespace goliatone\logger\flatg;

use \ReflectionMethod;
use \ReflectionFunction;

/**
 * Class Debugger
 * @package goliatone\logger
 */
class Debugger
{


    /**
     * This appear in the backtrace,
     * but we want to handle them differently.
     *
     * @var array
     */
    static protected $_statements = array( 'include', 'include_once', 'require', 'require_once' );

    /**
     * Generate custom output for a backtrace.
     *
     * @param array $ignore
     * @return array
     *
     */
    static public function backtrace($level = 1, $ignore = array())
    {
        $out    = array();
        $ignore = array_merge($ignore, array(__FUNCTION__, ''));

        $backtrace = array_slice(debug_backtrace(), $level);

        $keys   = array('function', 'class', 'type', 'args', 'file', 'line');
        $sizeOf = sizeof($keys);

        $getValue = function($k, $v, $default = ''){
            return isset($v[$k]) ? $v[$k] : $default;
        };

        foreach($backtrace as $_ => $value)
        {
            $function = $getValue('function', $value);

            if(in_array($function, $ignore)) continue;

            $trace = array();

            for($i=0; $i < $sizeOf; $i++)
            {
                $key         = $keys[$i];
                $trace[$key] = $getValue($key, $value);
            }

            # Handle statements- include/require
            /*if(!empty($trace['args']) && in_array($function, self::$_statements))
            {
                $trace['args'] = array(self::cleanPath($trace['args'][0]));
            }*/

            self::_getArguments($trace);

            if(isset($trace['class']))
                $trace['call'] = $trace['class'].$trace['type'].$function;

            $trace['basename'] = basename($trace['file']);
            $trace['source']   = self::getSource($trace['file'], $trace['line']);

            $out[] = $trace;
        }

        return $out;

    }

    /**
     * @param $file
     * @param $lineNumber
     * @param int $padding
     * @return array|null
     */
    static public function getSource($file, $lineNumber, $padding = 7)
    {
        if(!$file OR !is_readable($file)) return NULL;

        $line = 0;
        $file = fopen($file, 'r');

        $end   = $lineNumber + $padding;
        $start = $lineNumber - $padding;

        $output = array();

        while(($row = fgets($file)) !== FALSE)
        {
            if(++$line > $end) break;
            if($line < $start) continue;

            $output[$line] = $row;
        }

        fclose($file);

        return $output;
    }

    public function formatCode($code, $lineNumber = -1, $padding = 7)
    {
        $source = '';
        $end    = $lineNumber + $padding;
        #Zero padding for line numbers.
        $format = '% '.strlen($end).'d';

        foreach($code as $line => $row)
        {
            $row = htmlspecialchars($row, ENT_QUOTES);
            $row = preg_replace('/[ ](?=[^>]*(?:<|$))/', '&nbsp', $row);
            $row = '<span>'.sprintf($format, $line).'</span>'.$row;

            if($line === $lineNumber)
            {
                $row = '<div class="highlight">'.$row.'</div>';
            } else {
                $row = "<div>{$row}</div>";
            }

            $source .= $row;
        }

        return $source;
    }

    static public function cleanPath($path, $line = null)
    {
        return $path;
    }

    static protected function _getArguments(& $trace)
    {
        extract($trace, EXTR_SKIP);

        if(!isset($args)) return;

        $params = null;

        if( !empty($class) || function_exists($function))
        {
            try {
                if(isset($class))
                {
                    switch(!method_exists($class, $function))
                    {
                        case isset($type) && $type === '::':
                            $function = '__callStatic';
                            break;
                        default:
                            $function = '__call';
                            break;
                    }

                    $reflection = new ReflectionMethod($class, $function);

                } else {
                    $reflection = new ReflectionFunction($function);
                }

                $params = $reflection->getParameters();

            } catch (Exception $e) {
                #this might go on silently...
            }
        }

        $arguments = array();

        foreach($trace['args'] as $i => $arg)
        {
            $key = isset($params[$i]) ? $params[$i]->name : $i;
            $arguments[$key] = $arg;
        }

        $trace['args'] = $arguments;
    }
}