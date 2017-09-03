<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
include_once(ROOT.'/lib/VO/CalcOrder.class.php');
class CalcAPI extends Component
{
    public function __construct()
    {
        parent::__construct();
        include_once(ROOT . '/lib/VO/CompanyLogo.class.php');
    }

    protected function registerActions()
    {
        $this->actions['default'] = 'getParams';
        $this->actions['params'] = 'getParams';
        $this->actions['set_params'] = 'setParams';
        $this->actions['select_company'] = 'selectCompany';
        $this->actions['get'] = 'getPolis';
        $this->actions['save'] = 'savePolis';
    }

    public function getParams()
    {
        //Core::debugDB();
        $paramList = new DBCollection('sr_calc_param', array('id', 'title'));
        $paramList->fetch('', '`order` asc');
        $valueList = new DBCollection('sr_calc_param_value', array('id', 'param_id', 'title'));
        $valueList->fetch('', '`id` asc');
        foreach ($valueList->data as $id => $value) {
            $paramList->data[$value['param_id']]['values'][$id] = $value;
        }
        $this->sendJSON($paramList->data);
    }
    
    public function setParams($input=null){
        $params = array();
        if (!$input) $input=$_POST['params'];
        foreach ($input as $parId => $value) {
            $params[(int)$parId] = (int)$value['id'];
        }
        if (count($params) != 4) exit();
        $valueList = new DBCollection('sr_calc_param_value');
        $valueList->fetch('id in (' . implode(',', $params) . ')');
        $selectedParams = array();
        foreach ($valueList->data as $valId => $value) {
            $selectedParams[$value['param_id']] = $value;
        }
        if (count($params) != 4) exit();
        $_SESSION['polis_params'] = $selectedParams;
        unset($_SESSION['polis_variants']);
        unset($_SESSION['polis_company']);
        $this->calculate();
    }
    public function calculate()
    {
        $params=$_SESSION['polis_params'];
        $companyList = new DBCollection('sr_calc_company');
        $companyList->fetch('', '`title` asc');
        $result = array();
        foreach ($companyList->data as $company) {
            $company['price'] = round((BASE_PAYMENT * $params[1]['value'] * $params[2]['value'] * $params[3]['value']),2);
            if ($company['cbm_limit'] > $params[4]['value']) $company['bonus_price'] = round(($company['price'] * $company['cbm_limit']),2);
            else $company['bonus_price'] = round(($company['price'] * $params[4]['value']),2);
            $result[$company['id']] = array(
                'id' => $company['id'],
                'title' => $company['title'],
                'price' => $company['price']."",
                'bonus_price' => $company['bonus_price']."",
                'logo' => $company['logo'] ? '/media/companies/' . $company['id'] . '_thumb.jpg' : null
            );
        }
        $_SESSION['polis_variants'] = $result;
        $this->sendJSON(array('companies'=>$result,'osago'=>array('price'=>OSAGO_PRICE,'sum'=>OSAGO_SUM)));
    }
    public function selectCompany(){
        if (!$_POST['company'] || !$_SESSION['polis_variants']) {
            $this->sendJSON(array('result'=>'error'));
        }
        $_SESSION['polis_company']=$_SESSION['polis_variants'][$_POST['company']];

        if (!$_SESSION['polis_company']) $this->sendJSON(array('result'=>'error'));
        $this->sendJSON(array(
            'result'=>'success',
            'price'=>$_SESSION['polis_company']['bonus_price'],
            'osago'=>$_POST['osago']? OSAGO_PRICE : 0,
            'total'=>$_POST['osago']? (OSAGO_PRICE + $_SESSION['polis_company']['bonus_price']) : $_SESSION['polis_company']['bonus_price']
        ));
    }

    public function savePolis(){
        if (!is_array($_POST['polis'])) $this->sendJSON(array('result'=>'error'));
        $data=$_POST['polis'];
        $data['params']=$_SESSION['polis_params'];
        $data['price']=$_SESSION['polis_company']['bonus_price'];
        $data['osago']=$data['osago']? OSAGO_PRICE : 0;
        $data['total']=$data['osago']? (OSAGO_PRICE + $_SESSION['polis_company']['bonus_price']) : $_SESSION['polis_company']['bonus_price'];
        $polis = new CalcOrder('sr_calc_order',$_SESSION['polis_id']);
        $polis->set($data,true);
        $polis->save();
        $_SESSION['polis_id']=$polis->id;
        if ($polis->fetch())
            $this->sendJSON(array('result'=>'success','data'=>$polis->getAll()));
        else
            $this->sendJSON(array('result'=>'error'));
    }

    public function sendJSON($data)
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

}
