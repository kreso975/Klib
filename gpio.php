<?php
/**
 * Created by PhpStorm.
 * User: Kokanovic
 * Date: 31/03/2017
 * Time: 17:44
 */
// require_once 'Omega2lib.php';

/**
 * @author     Ashraf M Kaabi
 * @name       Advance Linux Exec
 */
class exec {
    /**
     * Run Application in background
     *
     * @param     unknown_type $Command
     * @param     unknown_type $Priority
     * @return     PID
     */
    function background($Command, $Priority = 0){
        if($Priority)
            $PID = shell_exec("nohup nice -n $Priority $Command > /dev/null & echo $!");
        else
            $PID = shell_exec("nohup $Command > /dev/null & echo $!");
        return($PID);
    }
    /**
     * Check if the Application running !
     *
     * @param     unknown_type $PID
     * @return     boolen
     */
    function is_running($PID){
        exec("ps $PID", $ProcessState);
        return(count($ProcessState) >= 2);
    }
    /**
     * Kill Application PID
     *
     * @param  unknown_type $PID
     * @return boolen
     */
    function kill($PID){
        if(exec::is_running($PID)){
            exec("kill -KILL $PID");
            return true;
        }else return false;
    }
};

$test = New exec();




$command = "fast-gpio pwm 1 1 50 > /dev/null & echo $!";
$result = exec( $command );

sleep ( 3 );

$command = "fast-gpio set 1 0 > /dev/null & echo $!";
shell_exec( $command );

//proc_open($command, $descriptorspec, $pipes);

//$test->background($command, 0);

?>
