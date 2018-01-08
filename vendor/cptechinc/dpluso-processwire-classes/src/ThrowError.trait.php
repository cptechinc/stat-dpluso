<?php 
    trait ThrowErrorTrait {
        protected function error($error, $level = E_USER_ERROR) {
            $trace = debug_backtrace();
            $caller = next($trace); 
            $class = get_class($this);
			$error = (strpos($error, "DPLUSO [$class]: ") !== 0 ? "DPLUSO [$class]: " . $error : $error);
            $error .= $caller['function'] . " called from " . $caller['file'] . " on line " . $caller['line']; 
			trigger_error($error, $level);
			return;
		}
    }
