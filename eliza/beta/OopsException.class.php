<?php

namespace eliza\beta;

class OopsException extends \Exception {    
    private static function path($_real_path) {
        return preg_replace('/' . str_replace('\\', '\\\\', substr(ROOT, 0, -1)) . '/', '', $_real_path);
    }

    public function printStackTrace() {
        $stack_trace = $this->getTrace();
        $string_trace = "\n<br /><strong>" . get_class($this) . '</strong>: ' 
                      . $this->getMessage() . "<br />"
                      . "\n#0 in " . self::path($this->getFile()) . ' on line ' . $this->getLine()
                      . ': ' . $this->getMessage() . '<br \>';
        
        for ($i = 1; $i <= count($stack_trace); $i++) {
            
            // get file and line where runtime halts
            if (key_exists('file', $stack_trace[$i-1])) {
                $string_trace .= "\n#" . $i . ' in ' . self::path($stack_trace[$i-1]['file']);
                $string_trace .= ' on line ' . $stack_trace[$i-1]['line'] . ': ';
            } else
                $string_trace .= "\n#" . $i . ' [internal function]: ';
            
            // check if it is a class that halts 
            if (array_key_exists('class', $stack_trace[$i-1])) {
                $string_trace .= $stack_trace[$i-1]['class'];
                $string_trace .= $stack_trace[$i-1]['type'] ;
            }
            
            // function or method that halts
            $string_trace .= $stack_trace[$i-1]['function'] . '(';
            
            // parses function or method's parameters if any
            if (array_key_exists('args', $stack_trace[$i-1])) {
                for ($c = 0; $c < count($stack_trace[$i-1]['args']); $c++) {
                                        
                    // is argument null?
                    if (is_null($stack_trace[$i-1]['args'][$c]))
                        $stack_trace[$i-1]['args'][$c] = 'NULL';
                    
                    // is argument a boolean false?
                    else if ($stack_trace[$i-1]['args'][$c] === false)
                        $stack_trace[$i-1]['args'][$c] = 'FALSE';
                    
                    // is argument a boolean true?
                    else if ($stack_trace[$i-1]['args'][$c] === true)
                        $stack_trace[$i-1]['args'][$c] = 'TRUE';
                    
                    // is argument an array?
                    else if (is_array($stack_trace[$i-1]['args'][$c]))
                        $stack_trace[$i-1]['args'][$c] = 'Array';
                    
                    // get class name if parameter is an object
                    else if (is_object($stack_trace[$i-1]['args'][$c]))
                        $stack_trace[$i-1]['args'][$c] = 
                        'Object(' . get_class($stack_trace[$i-1]['args'][$c]) . ')';
                    
                    // trims string if too long
                    else if (strlen($stack_trace[$i-1]['args'][$c]) > 20)
                        $stack_trace[$i-1]['args'][$c] = '...' . substr ($stack_trace[$i-1]['args'][$c], -20);
                }
                
                // glue parameters together
                $string_trace .= implode(', ', $stack_trace[$i-1]['args']);
            }
            
            $string_trace .= ')<br />';
        }
        
        // print formatted stack trace
        echo $string_trace . "\n#" . $i++ . ' what the fuck dude!<br />';
    }
}