<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */


function smarty_function_today($params, $template)
{
    $dateArray = getdate();
    $monthList = array(
        'uk'=>array('січень','лютий','березень','квітень','травень','червень','липень','серпень','вересень','жовтень','листопад','грудень'),
        'ru'=>array('январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь')
    );
    $langCode = $params['lang']=='/ru' ? 'ru' : 'uk';
    if($params['prevMonth']){
        if($dateArray['mon']==1){
            $dateArray['year']--;
        }
        $dateArray['mon'] = $dateArray['mon']==1 ? $dateArray['mon']=12 : $dateArray['mon']-1;
    }
    $date = $monthList[$langCode][$dateArray['mon']-1].' '.$dateArray['year'].($langCode=='uk' ? ' року' : ' года');

    return $date;
}