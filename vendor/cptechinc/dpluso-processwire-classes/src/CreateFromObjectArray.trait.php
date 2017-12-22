<?php 
    trait CreateFromObjectArrayTraits {
        public static function create_fromarray(array $array) {
			$myClass = get_class();
			$object  = new $myClass(); 

			foreach ($array as $key => $val) {
				$object->$key = $val;
			}
			return $object;
		}
        
        public static function create_fromobject($object) {
            $myClass = get_class();
			$newobject  = new $myClass(); 
            
            foreach (get_class_vars(get_class()) as $property => $value) {
                $newobject->$property = $object->$property;
            }
			return $newobject;
        }
    }
