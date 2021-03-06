<?php

namespace eliza;

class Oops extends \Exception {    
    private static function path($_real_path) {
        return preg_replace('/' . preg_quote(substr(ROOT, 0, -1), '/') . '/', '', $_real_path);
    }

    public function printStackTrace() {
        $stack_trace = array_reverse($this->getTrace());
        $class = explode('\\', get_class($this));
        $string_trace = "\n<strong>" . end($class) . '</strong>: ' 
                      . $this->getMessage() . '<br \><br \>';
        
        for ($i = 0; $i < count($stack_trace); $i++) {
            // get file and line where runtime halts
            if (key_exists('file', $stack_trace[$i])) {
                $string_trace .= "\n#" . $i . ' in ' . self::path($stack_trace[$i]['file']);
                $string_trace .= ' on line ' . $stack_trace[$i]['line'] . ': ';
            } else
                $string_trace .= "\n#" . $i . ' internal call: ';
            
            // check if it is a class that halts 
            if (array_key_exists('class', $stack_trace[$i])) {
                $string_trace .= $stack_trace[$i]['class'];
                $string_trace .= $stack_trace[$i]['type'] ;
            }
            
            // if closure
            if ($stack_trace[$i]['function'] == '{closure}') {
                $string_trace .= $stack_trace[$i]['args'][1] . '<br />';
                continue;
            }
            
            // function or method that halts
            $string_trace .= $stack_trace[$i]['function'] . '(';
            
            // parses function or method's parameters if any
            if (array_key_exists('args', $stack_trace[$i])) {
                for ($c = 0; $c < count($stack_trace[$i]['args']); $c++) {
                                        
                    // is argument null?
                    if (is_null($stack_trace[$i]['args'][$c]))
                        $stack_trace[$i]['args'][$c] = 'NULL';
                    
                    // is argument a boolean false?
                    else if ($stack_trace[$i]['args'][$c] === false)
                        $stack_trace[$i]['args'][$c] = 'FALSE';
                    
                    // is argument a boolean true?
                    else if ($stack_trace[$i]['args'][$c] === true)
                        $stack_trace[$i]['args'][$c] = 'TRUE';
                    
                    // is argument an array?
                    else if (is_array($stack_trace[$i]['args'][$c]))
                        $stack_trace[$i]['args'][$c] = 'Array[' . count($stack_trace[$i]['args'][$c]) . ']';
                    
                    // get class name if parameter is an object
                    else if (is_object($stack_trace[$i]['args'][$c]))
                        $stack_trace[$i]['args'][$c] = 
                        'Object{' . get_class($stack_trace[$i]['args'][$c]) . '}';
                    
                    // trims string if too long
                    else if (strlen($stack_trace[$i]['args'][$c]) > 20)
                        $stack_trace[$i]['args'][$c] = 'String';
                }
                
                // glue parameters together
                $string_trace .= implode(', ', $stack_trace[$i]['args']);
            }
            
            $string_trace .= ')<br />';
        }
        
        // print formatted stack trace
        echo $string_trace . "\n#" . $i++ . ' what the fuck dude!<br />';
    }
}