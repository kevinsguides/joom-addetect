<?php

/**
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

//module helper
use Joomla\CMS\Helper\ModuleHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;

class PlgSystemAddetect extends CMSPlugin{
    


    public function onBeforeRender(){

        $app = Factory::getApplication();

        if (!$app->isClient('site')) {
            return;
        }

        $wam = $app->getDocument()->getWebAssetManager();

        $plugin_dir = JUri::root() . 'plugins/system/addetect';

        $params = $this->params;
        $alerttype = $params->get('alerttype', 'default');
        $isdismissable = $params->get('dismissable', false);
        $showonce = $params->get('showonce', false);
        //append html to body
        $buffer = $app->getDocument()->getBuffer('component');

 
        //generate a random string to use as a unique id
        $randomid = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

        $HTML = '';
        if ($alerttype == 'default'){
            $HTML = '
            <div id="'.$randomid.'">
            <div aria-hidden="true" style="display: none; position: absolute; width:100%; height: 100%; background: #000000b8; top: 0; left: 0; z-index: 10000; backdrop-filter: blur(3px);">
                <img src="'.$plugin_dir.'/media/default.svg" style="max-width: 600px; top:50%; left:50%; transform: translate(-50%, -50%); position:absolute;"/>
            </div>
            </div>
            ';
        }
        elseif ($alerttype == 'custommessage'){
            $custommessage = $params->get('custommessage', 'Please disable your ad blocker.');
            $HTML = '
            <div id="'.$randomid.'">
            <div aria-hidden="true" style="display: none; position: absolute; width:100%; height: 100%; background: #000000b8; top: 0; left: 0; z-index: 10000; backdrop-filter: blur(3px);">
                <div style="max-width: 600px; top:50%; left:50%; transform: translate(-50%, -50%); position:absolute; background: #b20000;">
                    <p style="color: white; font-size: 2.5rem; font-weight: 700; text-align: center;">'.$custommessage.'</p>
                </div>
            
            </div>
            </div>
            ';
        }

        if($alerttype == 'custommessage' || $alerttype == 'default'){

            if($isdismissable == true){
                $HTML .= '<script type="text/javascript">
    
                let showjustonce = '.$showonce.';
                let dismissable = true;
    
                console.log(showjustonce);
                
                if (showjustonce){
                    if (localStorage.getItem("showzies") !== null) {
                    }
                    else{
                        showDialog();
                    }
                }
                else{
                    showDialog();
                }
    
                ';
            }
            else{
                $HTML .= '<script type="text/javascript">
                let showjustonce = false;
                let dismissable = false;
                showDialog();';
            }

            $HTML .= '
            function showDialog(){
                setTimeout(function() {
                    fetch("https://www3.doubleclick.net", {
                    method: "HEAD",
                    mode: "no-cors",
                    cache: "no-store",
                    }).catch(()=>{
                            document.getElementById("'.$randomid.'").firstElementChild.style.display = "block";
                            if(dismissable){
                                document.getElementById("'.$randomid.'").firstElementChild.addEventListener("click", function(){
                                    document.getElementById("'.$randomid.'").firstElementChild.style.display = "none";
                                        
                                    if (showjustonce == 1){
                                        if (localStorage.getItem("showzies") === null) {
                                            localStorage.setItem("showzies", "true");
                                        }
                                    }
                                
                                });
                            }
                    });
                    }, 3500);
            }
            </script>';

        }

        if($alerttype == 'redirect'){
            $redirecturl = $params->get('redirecturl', 'https://www.google.com');
            $HTML .= '<script type="text/javascript">
            setTimeout(function() {
                fetch("https://www3.doubleclick.net", {
                method: "HEAD",
                mode: "no-cors",
                cache: "no-store",
                }).catch(()=>{
                        window.location.href = "'.$redirecturl.'";
                });
                }, 3500);
            </script>';

        }

        if($alerttype == 'module'){

            //get mod_abd
            $module = ModuleHelper::getModule('mod_abd');

            $module->content = '<div id="'.$randomid.'" style="display: none;">'.$module->content.'</div>';

            $HTML = '
            <script type="text/javascript">
            setTimeout(function() {
                fetch("https://www3.doubleclick.net", {
                method: "HEAD",
                mode: "no-cors",
                cache: "no-store",
                }).catch(()=>{
                        document.getElementById("'.$randomid.'").style.display = "block";
                });
                }, 3500);
            </script>
            ';

        }
        

        


      
        
        $buffer .= $HTML;

        JFactory::getApplication()->getDocument()->setBuffer($buffer, 'component');

    }


}


