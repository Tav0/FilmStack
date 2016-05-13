<?php
class Helper {
    /*
     * Return a string of the specified time $timestamp
     * precondition: $timestamp has the format of the MySQL Timestamp data type
     */
    public static function monthDateYear($timestamp) {
        return date('F j, Y', strtotime($timestamp));
    }
    
    /*
     * Return the current time in the format of the MySQL Timestamp data type
     */
    public static function currentTimestampMysqlFormat() {
        return date('Y-m-d G:i:s');
    }
    
    /*
     * Return a string of clock time
     * precondition: $timestamp has the format of the MySQL Timestamp data type
     */
    public static function clockTimeString($timestamp) {
        return date('g:i a', strtotime($timestamp));
    }
    /*
     * Return string 's' if $quantity is zero or greater than one
     */
    private static function pluralLetterS($quantity) {
        return ($quantity == 0 || $quantity > 1) ? 's' : '';
    }
    
    /*
     * Return a string of how much time has elapsed since $begin and $end
     * precondition: $begin and $end have the format of the MySQL
     *               Timestamp data type
     */
    public static function relativeTime($begin, $end) {
        $end = strtotime($end);
        $begin = strtotime($begin);
        $difference = $end - $begin;
        
        $numMinsDifference = floor($difference / 60);
        $numHours = floor($numMinsDifference / 60);
        $numMins = $numMinsDifference - ($numHours * 60);
        
        $hourOrHours = 'hour'.Helper::pluralLetterS($numHours);
        $minuteOrMinutes = 'minute'.Helper::pluralLetterS($numMins);

        $string = '';
        // if within one minute: "just now"
        if ($numHours == 0 && $numMins == 0) {
            $string = "just now";
        }
        else {
            // numHours is nonzero, but minutes is zero
            if ($numHours != 0 && $numMins == 0) {            
                // e.g. "4 hours"
                $string = "{$numHours} {$hourOrHours}";
            }
            // numHours is zero, but minutes is nonzero
            else if ($numHours == 0 && $numMins != 0) {
                // e.g. "30 minutes"
                $string = "{$numMins} {$minuteOrMinutes}";            
            }
            // numHours and numMins are both nonzero
            else {
                $string = "{$numHours} {$hourOrHours} and {$numMins} {$minuteOrMinutes}";
            }
            $string = "{$string} ago";
        }
        return $string;
    }
}
