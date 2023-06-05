<?php
/**
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined ( '_JEXEC' ) or die;

use Joomla\CMS\Factory;


$adblocker_message_html = $params->get('message', 'Please disable your ad blocker.');

$wrapper = $params->get('wrapper', 'none');
$start = '';
$end = '';

if($wrapper == 'bscard'){
    $start = '<div class="card"><div class="card-body">';
    $end = '</div></div>';
}

if($wrapper == 'bscarddanger'){
    $start = '<div class="card bg-danger text-white"><div class="card-body">';
    $end = '</div></div>';
}

if($wrapper == 'boxwhitered'){
    $start = '<div style="padding: 1rem; border-radius:1rem; background: #9b0000; color: white; ">';
    $end = '</div>';
}

?>
<?php echo($start); ?>
<?php echo($adblocker_message_html); ?>
<?php echo($end); ?>


