<?php

extract($_GET);

include 'cloud_install.php';
include 'school_install.php';
include 'user_install.php';

/*
 * Select Install Type
 */

switch($action){
    
    case 'cloud_install':

        break;
    
    default:
        $select_install .= "<option value=0> Select Install Type <option>";
        $select_install .= "<option value='cloud_install'> Install Cloud <option>";
        $select_install .= "<option value='school_install'> Install School <option>";
        $select_install .= "<option value='user_install'> Install User <option>";
        
        echo "<table>
		<tr><td>Install Options </td><td><select name='install_action' id='install_action'> $select_install   </select></td></tr>
            </table>";
        break;
    
}
