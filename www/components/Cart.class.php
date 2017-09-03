<?php
include_once "Product.class.php";

/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * $cart = array(
 * items=>
 * );
 */
class Cart extends Component
{
    public $oneClickOrder = false;
    private $productClass;

    public function __construct()
    {
        $this->productClass = new Product();
        parent::__construct();
    }

    protected function registerActions()
    {
        $this->actions['default'] = 'show';
        $this->actions['show'] = 'show';
        $this->actions['update'] = 'update';
        $this->actions['shipping'] = 'selectShipping';
        $this->actions['payment'] = 'selectPayment';
        $this->actions['submitOrder'] = 'submitOrder';
        $this->actions['contacts'] = 'setContacts';
        $this->actions['confirm'] = 'confirmOrder';
//        $this->actions['sms'] = 'testSMS';
        $this->actions['oneClick'] = 'oneClick';
        $this->actions['confirmOneClick'] = 'confirmOneClickOrder';
        $this->actions['liqPaySuccess'] = 'liqPaySuccess';
    }

    public function run($action = null)
    {
        if (!$action) {
            $action = Core::$path[1];
        }
        if ($this->actions[$action]) $this->{$this->actions[$action]}();
        else $this->{$this->actions['default']}();
    }

    public function show()
    {
        if (!$_SESSION['cart']) $this->createSessionCart();
        $this->recountCart();
        $cart = $_SESSION['cart'];
        $productFields = array('id', 'parent_id', 'title', 'sef', 'section_id', 'price', 'sale_price', 'stock', 'image');
        $products = array();
        $addedItems = new DBCollection('sr_shop_product', $productFields);
        $addedItems->fetch('id in (' . implode(',', array_keys($cart['items'])) . ')');
        $db = Core::getDB();
        foreach ($addedItems->data as $p) {
            $this->setTypes($p);
            if ($p['parent_id'] == 0) {
                array_push($products, $p);
                continue;
            }
            $pp = new DBObject('sr_shop_product', (int)$p['parent_id']);
            if (!$pp->id) continue;
            $parentProduct = array();
            foreach ($productFields as $f) {
                $parentProduct[$f] = $pp->get($f);
            }
            $parentProduct['id'] = $p['id'];
            $parentProduct['parent_id'] = $p['parent_id'];
            $parentProduct['option_name'] = $p['title'];
            $parentProduct['price'] = $p['price'];
            $parentProduct['sale_price'] = $p['sale_price'];
            $parentProduct['stock'] = $p['stock'];
            array_push($products, $parentProduct);
        }

        Core::$breadcrumbs['/articles'] = Lang::$locale['cart'];

        $smarty = Core::getSmarty();
        $smarty->assign('cart', $cart);
        $addressList = json_decode(Core::getSettings('shop', 'map'), true);
        $smarty->assign('addressList', $addressList['items']);
        $smarty->assign('products', $products);
        $smarty->assign('meta_title', Lang::$locale['cart']);
        $smarty->assign('component', 'cart');
    }

    public function setContacts()
    {
        if ($_SESSION['customer']) return;
        $allowedFields = array('name', 'mail', 'phone');
        $data = array();
        foreach ($allowedFields as $field) {
            if (isset($_POST[$field])) {
                $data[$field] = htmlspecialchars(trim($_POST[$field]));
            }
        }
        if (!$data['name']) {
            $this->sendJSON(array('result' => 'error',
                'data' => array(
                    'field' => 'name',
                    'message' => Lang::$locale['enter_your_name']
                )));
            return;
        }

        if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
            $this->sendJSON(array('result' => 'error',
                'data' => array(
                    'field' => 'mail',
                    'message' => Lang::$locale['enter_correct_email']
                )));
            return;
        }


        if (!$data['phone']) {
            $this->sendJSON(array('result' => 'error',
                'data' => array(
                    'field' => 'phone',
                    'message' => Lang::$locale['enter_contact_phone']
                )));
            return;
        }
        $_SESSION['cart']['contacts'] = $data;
        $this->sendJSON(array('result' => 'success', 'data' => $data));
    }

    public function selectShipping()
    {
        $method = $_POST['method'];
        $data = $_POST['data'];
        $validationRes = ShopCore::validateShipment($method, $data);
        if ($validationRes['result'] == 'success') {
            $_SESSION['cart']['shipment'] = $validationRes['data'];
        }
        $this->sendJSON($validationRes);
    }

    public function selectPayment()
    {
        $method = $_POST['method'];
        $validationRes = ShopCore::validatePayment($method);
        if ($validationRes['result'] == 'success') {
            $_SESSION['cart']['payment'] = $validationRes['data'];
        }
        $this->sendJSON($validationRes);
    }

    public function submitOrder()
    {
        $allowedFields = array('name', 'mail', 'phone','shipping_method','payment_method','office','data_ship');
        $result = 'error';
        $data = array();
        $errors = array();
        foreach ($allowedFields as $field) {
            if (isset($_POST[$field])) {
                $data[$field] = htmlspecialchars(trim($_POST[$field]));
            }
        }
        if (!$data['name']) {
            $errors[] = array(
                    'field' => 'name',
                    'message' => Lang::$locale['enter_your_name']
                );
        }

        if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
            $errors[] =  array(
                'field' => 'mail',
                'message' => Lang::$locale['enter_correct_email']
            );
        }


        if (!$data['phone']) {
            $errors[] =  array(
                'field' => 'phone',
                'message' => Lang::$locale['enter_contact_phone']
            );
        }
        if (!$data['shipping_method']) {
            $errors[] =  array(
                'field' => 'shipping_method',
                'message' => Lang::$locale['select_shipping_method']
            );
        } else {
            $method = $data['shipping_method'];
            $shipment_data = $_POST['data_ship'];
            $validationRes = ShopCore::validateShipment($method, $shipment_data, $errors);
            if ($validationRes['result'] != 'success') {
//                $_SESSION['cart']['shipment'] = $validationRes['data'];
                $errors =  $validationRes['errors'];
            } else {
                $_SESSION['cart']['shipment'] = $validationRes['data'];
            }
//            $this->sendJSON($validationRes);
        }



//        elseif ($data['shipping_method'] == 'pickup'){
//            if (!($data['office'])) {
//                $errors[] = array(
//                    'field' => 'office',
//                    'message' => Lang::$locale['select_office']
//                );
//            }
//        } elseif ($data['shipping_method'] == 'courier'){
//            if (!$data['courier_address']) {
//                $errors[] = array(
//                    'field' => 'courier_address',
//                    'message' => Lang::$locale['enter_address']
//                );
//            }
//        } elseif ($data['shipping_method'] == 'new_post'){
//            if (!$data['courier_address']) {
//                $errors[] = array(
//                    'field' => 'shipping_method',
//                    'message' => Lang::$locale['select_shipping_method']
//                );
//            }
//        } elseif ($data['shipping_method'] == 'post'){
//            if (!$data['address']) {
//                $errors[] = array(
//                    'field' => 'address',
//                    'message' => Lang::$locale['enter_address']
//                );
//            }
//        }

        if (!$data['payment_method']) {
            $errors[] =  array(
                'field' => 'payment_method',
                'message' => Lang::$locale['select_payment_method']
            );
        } else {
            $method = $data['payment_method'];
            $validationRes = ShopCore::validatePayment($method);

            if ($validationRes['result'] != 'success') {
//                $_SESSION['cart']['payment'] = $validationRes['data'];
                $errors =  $validationRes['errors'];
            } else {
                $_SESSION['cart']['payment'] = $validationRes['data'];
            }
//            $this->sendJSON($validationRes);

        }

        if (empty($errors)) {
            $result = 'success';
//            $this->confirmOrder();
        }
        $_SESSION['cart']['contacts'] = $data;
        $this->sendJSON(array('result' => $result, 'data' => $data, 'errors' => $errors));
        return;
    }

    public function update()
    {

        if (!$_SESSION['cart']) $this->createSessionCart();
        $mode = $_POST['mode'] ? $_POST['mode'] : 'add';
        $data = array(
            'id' => (int)$_POST['id'],
            'qty' => (int)$_POST['qty']
        );
        $product = new DBObject('sr_shop_product', $data['id']);

        $section = $this->productClass->getSection($product->get('section_id'));
        $top_section = $this->productClass->getTopSection($section);
        $currentQty = $_SESSION['cart']['items'][$product->id] && $mode == 'add' ? (int)$_SESSION['cart']['items'][$product->id] : 0;
        if (!$product->id) {
            $this->sendJSON(array(
                'result' => 'error',
                'message' => Lang::$locale['product_not_found']
            ));
            return;
        }
        $availableQty = (int)$product->get('stock') - (int)$product->get('reserved');
        if ($data['qty'] < 1 && $mode != 'delete') {
            $this->sendJSON(array('result' => 'error',
                'data' => array(
                    'field' => 'qty',
                    'message' => Lang::$locale['enter_correct_quantity']
                )));
            return;
        }

        if ($availableQty < 1 && $mode != 'delete') {
            $this->sendJSON(array('result' => 'error',
                'data' => array(
                    'field' => 'qty',
                    'message' => Lang::$locale['produc_not_in_stock']
                )));
            return;
        }
        if ($availableQty - $currentQty < $data['qty'] && $mode != 'delete') {
            $this->sendJSON(array('result' => 'error',
                'data' => array(
                    'field' => 'qty',
                    'message' => str_replace('{QTY}', $availableQty, Core::getSettings('shop', 'stock_error')),
                    'max_qty' => $availableQty
                )));
            return;
        }
        if ($mode == 'delete') {
            unset($_SESSION['cart']['items'][$product->id]);
        } else if (isset($_SESSION['cart']['items'][$product->id])) {
            if ($mode == 'replace') {
                $_SESSION['cart']['items'][$product->id]['qty'] = $data['qty'];
            } else {
                $_SESSION['cart']['items'][$product->id]['qty'] += $data['qty'];
            }
        } else {
            $_SESSION['cart']['items'][$product->id] = $this->getProductDataArray($product, $section, $top_section);
            $_SESSION['cart']['items'][$product->id]['qty'] = $data['qty'];
        }

        $this->recountCart();
        $this->sendJSON(array(
            'result' => 'success',
            'data' => array(
                'count' => $_SESSION['cart']['totalCount'],
                'price' => $_SESSION['cart']['totalPrice'],
                'totalDiscount' => $_SESSION['cart']['totalDiscount'],
                'discount' => $_SESSION['cart']['discount']
            ),
            //ession.cart.items
            'items' => isset($_SESSION['cart']['items']) ? $_SESSION['cart']['items'] : array()
        ));
    }


    function  confirmOrder()
    {
        $cart = $_SESSION['cart'];
        if (!$cart) {
            header('Location: /cart');
            exit();
        }

        if ($_SESSION['customer']) {
            $cart['contacts'] = array(
                'name' => $_SESSION['customer']['name'],
                'mail' => $_SESSION['customer']['mail'],
                'phone' => $_SESSION['customer']['phone']
            );
        }
        if (((!$cart['contacts'] || !$cart['payment'] || !$cart['shipment']) && !$this->isOneClickOrder()) || !count($cart['items'])) {
            header('Location: /cart');
            exit();
        }
        $status = $this->isOneClickOrder() ? 'oneClick' : 'new';
        $order = new DBObject('sr_shop_order');
        $order->set(array(
            'customer_id' => $_SESSION['customer'] ? $_SESSION['customer']['id'] : 0,
            'customer_name' => $cart['contacts']['name'],
            'customer_mail' => $cart['contacts']['mail'],
            'customer_phone' => $cart['contacts']['phone'],
            'date' => time(),
            'status' => $status,
            'total' => 0,
            'payment' => $cart['payment']['method'],
            'shipment' => $cart['shipment']['method'],
            'payment_data' => json_encode($cart['payment']),
            'shipment_data' => json_encode($cart['shipment'])
        ));
        $order->save();
        if (!$order->id) {
            header('Location: /cart');
            exit();
        }
        $discount = ShopCore::getDiscount();
        $totalPrice = 0;
        $products = array();
        foreach ($cart['items'] as $productId => $qty) {
            $p = new DBObject('sr_shop_product', (int)$productId);
            $this->setTypes($p);
            if (!$p->id) continue;
            if ($p->get('parent_id') != 0) {
                $m = $p;
                $p = new DBObject('sr_shop_product', $m->get('parent_id'));
                $this->setTypes($p);
            } else {
                $m = false;
            }
            if ($m) {
                if ($m->get('sale_price') > 0) {
                    $price = $m->get('sale_price');
                    $comment = 'Акция. Стандартная цена: ' . $m->get('price') . ' грн';
                } else {
                    if (!$discount) {
                        $price = $m->get('price');
                        $comment = '';
                    } else {
                        $price = round($m->get('price') * (1 - $discount));
                        $comment = 'Дисконт ' . ($discount * 100) . '%. Стандартная цена: ' . $m->get('price') . ' грн';
                    }
                }
                $m->set('reserved', $m->get('reserved') + $qty['qty']);
                $m->save();
            } else {
                if ($p->get('sale_price') > 0) {
                    $price = $p->get('sale_price');
                    $comment = 'Акция. Стандартная цена: ' . $p->get('price') . ' грн';
                } else {
                    if (!$discount) {
                        $price = $p->get('price');
                        $comment = '';
                    } else {
                        $price = round($p->get('price') * (1 - $discount));
                        $comment = 'Дисконт ' . ($discount * 100) . '%. Стандартная цена: ' . $p->get('price') . ' грн';
                    }
                }
                $p->set('reserved', $p->get('reserved') + $qty['qty']);
                $p->save();
            }

            $orderItem = new DBObject('sr_shop_order_item');
            $orderItem->set(array(
                'order_id' => $order->id,
                'product_id' => $m ? $m->id : $p->id,
                'code' => $m ? $m->get('code') : $p->get('code'),
                'title' => $p->get('title') . ($m ? ' - ' . $m->get('title') : ''),
                'price' => $price,
                'qty' => $qty['qty'],
                'comment' => $comment
            ));
            $orderItem->save();
            $item = $orderItem->getAll();
            $item['pid'] = $p->id;
            $item['sef'] = $p->get('sef');
            $item['image'] = $p->get('image');
            $item['section_id'] = $p->get('section_id');
            array_push($products, $item);
            $this->updateStockAndPrice($p);

            $totalPrice += $qty['qty'] * $price;
        }

        $order->set('total', $totalPrice);
        $order->save();

        $this->createSessionCart();
        $smarty = Core::getSmarty();
        include_once(ROOT . '/components/LiqPayPayment.class.php');
        $orderData = $order->getAll();
        $liqPay = new LiqPayPayment($orderData);
        $smarty->assign('liqPay', $liqPay);
        $smarty->assign('isCartPage', 1);
        $smarty->assignByRef('order', $order);
        $smarty->assign('payment', $order->get('payment'));
        $smarty->assign('meta_title', Lang::$locale['order_created']);
        if (!$this->isOneClickOrder()) {
            $smarty->assign('component', 'order_confirm');
        } else {
            $smarty->assign('component', 'order_click_confirm');
        }

        $orderData['payment_data'] = json_decode($orderData['payment_data'], true);
        $orderData['shipment_data'] = json_decode($orderData['shipment_data'], true);
        if (!$this->isOneClickOrder()) {
            $this->sendLetter(
                'order',
                $order->get('customer_mail'),
                'Замовлення на сайті "Імідж"',
                array(
                    'order' => $orderData,
                    'products' => $products,
                    'bank_info' => Core::getSettings('shop', 'bank'),
                    'phones' => Core::getSettings('main', 'phone')
                )
            );
            $this->sendLetter(
                'order_admin',
                Core::getSettings('main', 'admin_mail'),
                'Нове замовлення №' . $order->id,
                array(
                    'order' => $orderData,
                    'products' => $products,
                    'bank_info' => Core::getSettings('shop', 'bank')
                )
            );
        } else {
            $this->sendLetter(
                'order_admin',
                Core::getSettings('main', 'admin_mail'),
                'Нове замовлення №' . $order->id,
                array(
                    'order' => $orderData,
                    'products' => $products,
                    'bank_info' => Core::getSettings('shop', 'bank')
                )
            );
        }


        include_once(ROOT . '/lib/smsclient.class.php');
//        init class with your login/password
        $sms = new SMSclient('380674439777', '30052002');
        $id = $sms->sendSMS('Imidg', Core::getSettings('main', 'admin_phone'), 'Нове замовлення на сайті imidg.com.ua №' . $orderData['id'] . '. Сума ' . $orderData['total'] . ' грн.');
//        if no ID - then message is not sent and you should check errors
        if (!$id) {
            Core::log('Error sending SMS: ' . var_export($sms->getErrors(), true));
        } else {
            Core::log('SMS sent successfully: ' . var_export($id, true));
        }
//        $sms->sendSMS('Imidg', '380677662069', 'Нове замовлення на сайті imidg.com.ua №' . $orderData['id'] . '. Сума ' . $orderData['total'] . ' грн.');
        unset($_SESSION['oneClickOrder']);
    }

    public function isOneClickOrder()
    {
        if ($_SESSION['cart']['oneClickOrder']) {
            return true;
        }
        return false;
    }

    public function testSMS()
    {
        include_once(ROOT . '/lib/smsclient.class.php');
        $sms = new SMSclient('380674439777', '30052002');
        $id = $sms->sendSMS('Imidg', '380967093931', 'Нове замовлення на сайті imidg.com.ua №0000 Сума 00000 грн.');
        //if no ID - then message is not sent and you should check errors
        if (!$id) {
            var_dump($sms->getErrors());
        } else {
            var_dump($id);
        }
        exit();
    }

    public function updateStockAndPrice($product)
    {
        if (!($product instanceof DBObject)) {
            $product = new DBObject('sr_shop_product', (int)$product);
        }
        if (!$product->id) return false;
        $modificationsList = new DBCollection('sr_shop_product', array('id', 'parent_id', 'price', 'sale_price', 'stock', 'reserved'));
        $modificationsList->fetch('`parent_id`=' . (int)$product->id);
        $minPrice = false;
        $maxPrice = false;
        $inStock = 0;
        $isSale = 0;
        if (is_array($modificationsList->data) && count($modificationsList->data)) {
            foreach ($modificationsList->data as $m) {
                $price = (float)$m['sale_price'] > 0 ? (float)$m['sale_price'] : (float)$m['price'];
                $stock = (int)$m['stock'] - (int)$m['reserved'];
                if ($stock) {
                    if ($minPrice === false || $minPrice > $price) $minPrice = $price;
                    if ($maxPrice === false || $maxPrice < $price) $maxPrice = $price;
                }
                if ($stock) $inStock = 1;
                if ((float)$m['sale_price'] > 0) $isSale = 1;
            }
        } else {
            $inStock = (int)$product->get('stock') - (int)$product->get('reserved') > 0 ? 1 : 0;
            $isSale = (float)$product->get('sale_price') > 0 ? 1 : 0;
        }

        $productPrice = (float)$product->get('sale_price') > 0 ? (float)$product->get('sale_price') : (float)$product->get('price');
        if ($minPrice === false) $minPrice = $productPrice;
        if ($maxPrice === false) $maxPrice = $productPrice;
        $product->set(array(
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'instock' => $inStock,
            'sale' => $isSale
        ));
        $product->save();
    }

    public function oneClick()
    {
        if (!$_SESSION['cart']) $this->createSessionCart();

        $id = htmlspecialchars(trim($_POST['id']));
        $product = new DBCollection('sr_shop_product', array('id'));
        $product->fetch("id = {$id}");
        if (!empty($product->data)) {
            $_SESSION['cart']['oneClickOrder'] = $id;
        } else {
            $this->sendJSON(array('result' => 'error'));
            return false;
        }

        if (!$_POST['phone']) {
            $this->sendJSON(array('result' => 'error',
                'data' => array(
                    'field' => 'phone',
                    'message' => Lang::$locale['enter_contact_phone']
                )));
            return false;
        }
        $_SESSION['cart']['contacts']['phone'] = htmlspecialchars(trim($_POST['phone']));
        $_SESSION['cart']['contacts']['name'] = htmlspecialchars(trim($_POST['name']));
        $this->confirmOneClickOrder();
        $this->sendJSON(array('result' => 'success'));
    }

    function  confirmOneClickOrder()
    {
        $cart = $_SESSION['cart'];
        if (!$cart) {
            header('Location: /cart');
            exit();
        }

        if ($_SESSION['customer']) {
            $cart['contacts'] = array(
                'name' => $_SESSION['customer']['name'],
                'mail' => $_SESSION['customer']['mail'],
                'phone' => $_SESSION['customer']['phone']
            );
        }
        if (!$cart['oneClickOrder']) {
            header('Location: /cart');
            exit();
        }
        //oneClick
        $order = new DBObject('sr_shop_order');
        $order->set(array(
            'customer_id' => $_SESSION['customer'] ? $_SESSION['customer']['id'] : 0,
            'customer_name' => $cart['contacts']['name'],
            'customer_mail' => $cart['contacts']['mail'],
            'customer_phone' => $cart['contacts']['phone'],
            'date' => time(),
            'status' => 'oneClick',
            'total' => 0,
        ));
        $order->save();
        if (!$order->id) {
            header('Location: /cart');
            exit();
        }
        $discount = ShopCore::getDiscount();
        $products = array();
        $productId = $cart['oneClickOrder'];
        $qty = 1;
        $p = new DBObject('sr_shop_product', (int)$productId);
        $this->setTypes($p);
        if ($p->get('parent_id') != 0) {
            $m = $p;
            $p = new DBObject('sr_shop_product', $m->get('parent_id'));
            $this->setTypes($p);
        } else {
            $m = false;
        }
        if ($m) {
            if ($m->get('sale_price') > 0) {
                $price = $m->get('sale_price');
                $comment = 'Акция. Стандартная цена: ' . $m->get('price') . ' грн';
            } else {
                if (!$discount) {
                    $price = $m->get('price');
                    $comment = '';
                } else {
                    $price = round($m->get('price') * (1 - $discount));
                    $comment = 'Дисконт ' . ($discount * 100) . '%. Стандартная цена: ' . $m->get('price') . ' грн';
                }
            }
            $m->set('reserved', $m->get('reserved') + $qty);
            $m->save();
        } else {
            if ($p->get('sale_price') > 0) {
                $price = $p->get('sale_price');
                $comment = 'Акция. Стандартная цена: ' . $p->get('price') . ' грн';
            } else {
                if (!$discount) {
                    $price = $p->get('price');
                    $comment = '';
                } else {
                    $price = round($p->get('price') * (1 - $discount));
                    $comment = 'Дисконт ' . ($discount * 100) . '%. Стандартная цена: ' . $p->get('price') . ' грн';
                }
            }
            $p->set('reserved', $p->get('reserved') + $qty);
            $p->save();
        }

        $orderItem = new DBObject('sr_shop_order_item');
        $orderItem->set(array(
            'order_id' => $order->id,
            'product_id' => $m ? $m->id : $p->id,
            'code' => $m ? $m->get('code') : $p->get('code'),
            'title' => $p->get('title') . ($m ? ' - ' . $m->get('title') : ''),
            'price' => $price,
            'qty' => $qty,
            'comment' => $comment
        ));
        $orderItem->save();
        $item = $orderItem->getAll();
        $item['pid'] = $p->id;
        $item['sef'] = $p->get('sef');
        $item['image'] = $p->get('image');
        $item['section_id'] = $p->get('section_id');
        array_push($products, $item);
        $this->updateStockAndPrice($p);

        $totalPrice = $qty * $price;

        $order->set('total', $totalPrice);
        $order->save();
        $smarty = Core::getSmarty();
        $smarty->assignByRef('order', $order);
        $smarty->assign('meta_title', Lang::$locale['order_created']);
        if (!$this->isOneClickOrder()) {
            $smarty->assign('component', 'order_confirm');
        } else {
            $smarty->assign('component', 'order_click_confirm');
        }

        $orderData = $order->getAll();

        $this->sendLetter(
            'one_click_mail',
            Core::getSettings('main', 'admin_mail'),
            'Нове замовлення в один клік №' . $order->id,
            array(
                'order' => $orderData,
                'products' => $products,
            )
        );


        include_once(ROOT . '/lib/smsclient.class.php');
//        init class with your login/password
        $sms = new SMSclient('380674439777', '30052002');
        $id = $sms->sendSMS('Imidg', Core::getSettings('main', 'admin_phone'), 'Нове замовлення на сайті imidg.com.ua №' . $orderData['id'] . '. Сума ' . $orderData['total'] . ' грн.');
//        if no ID - then message is not sent and you should check errors
        if (!$id) {
//            print_r($sms->getErrors());
//            die();
            Core::log('Error sending SMS: ' . var_export($sms->getErrors(), true));
        } else {
//            print_r($sms->getErrors());
//            die();
            Core::log('SMS sent successfully: ' . var_export($id, true));
        }
//        $sms->sendSMS('Imidg', '380671313714', 'Нове замовлення на сайті imidg.com.ua №' . $orderData['id'] . '. Сума ' . $orderData['total'] . ' грн.');
        unset($_SESSION['cart']['oneClickOrder']);
    }

    public static function createSessionCart()
    {
        $_SESSION['cart'] = array(
            'items' => array(),
            'totalCount' => 0,
            'totalPrice' => 0,
            'totalDiscount' => 0,
            'oneClickOrder' => false
        );
    }

    public function liqPaySuccess()
    {
        $smarty = Core::getSmarty();
        $smarty->assign('component', 'liqpay_success');
    }

    public static function recountCart()
    {
        if (!$_SESSION['cart']) {
            Cart::createSessionCart();
            return;
        }
        $cart = &$_SESSION['cart'];
        $discount = ShopCore::getDiscount();
        $products = new DBCollection('sr_shop_product', array('id', 'price', 'sale_price', 'stock', 'reserved'));
        $products->fetch('id in (' . implode(',', array_keys($cart['items'])) . ')');
        $totalPrice = 0;
        $totalCount = 0;
        $totalDiscount = 0;


        foreach ($products->data as $product) {

            if ((int)$product['stock'] == 0) {
                unset($cart['items'][$product['id']]);
                continue;
            }
            if ((float)$product['sale_price'] > 0) {
                $price = (float)$product['sale_price'];
            } else {
                if ($discount) {
                    $price = round((float)$product['price'] * (1 - $discount));
                    $totalDiscount += $cart['items'][$product['id']]['qty'] * round((float)$product['price'] * $discount);
                } else {
                    $price = (float)$product['price'];
                }

            }

            $totalCount += $cart['items'][$product['id']]['qty'];
            $totalPrice += $cart['items'][$product['id']]['qty'] * $price;
        }
        $cart['totalCount'] = $totalCount;
        $cart['totalPrice'] = $totalPrice;
        $cart['totalDiscount'] = $totalDiscount;
        $cart['discount'] = $discount;
    }

    private function setTypes(&$product)
    {
        $isObject = $product instanceof DBObject;
        $intProp = array('id', 'section_id', 'parent_id', 'order', 'stock', 'reserved', 'active', 'home', 'new', 'hit', 'sale');
        $floatProp = array('price', 'sale_price');
        foreach ($intProp as $name) {
            if ($isObject) {
                $product->set($name, (int)$product->get($name));
            } else {
                if (!array_key_exists($name, $product)) continue;
                $product[$name] = (int)$product[$name];
            }

        }
        foreach ($floatProp as $name) {
            if ($isObject) {
                $product->set($name, (float)$product->get($name));
            } else {
                if (!array_key_exists($name, $product)) continue;
                $product[$name] = (float)$product[$name];
            }
        }
    }

    private function getProductDataArray($product, $section, $top_section)
    {
        $parent_id = $product->get('parent_id');
        $data = array(
            'title' => $product->get('title'),
            'price' => $product->get('price'),
            'sale_price' => $product->get('sale_price'),
            'image' => $product->get('image'),
            'parent_id' => $product->get('parent_id'),
            'stock' => $product->get('stock')
        );
        if (!empty($top_section['options'][63])) {
            $option = $this->productClass->fillOptions($top_section['options'], $product->id);
            $data['color'] =$option[63]['value'];
        }
        if ($parent_id != 0) {
            $parent = new DBObject('sr_shop_product', $parent_id);
            $data['parent_title'] = $parent->get('title');
            $data['image'] = $parent->get('image');
        }
        return $data;
    }


}
