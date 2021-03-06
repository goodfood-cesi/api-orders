<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PaypalClient;
use App\Mails\OrderConfirmed;
use App\Models\Order;
use App\Models\OrdersMenus;
use App\Models\OrdersProducts;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PaymentController extends Controller {

    public function init(Request $request) {
        try {
            $this->validate($request, [
                'cart' => 'required|array',
                'restaurant' => 'required|int'
            ]);

            $restaurant = $this->fetch('/restaurants/' . $request->input('restaurant'));
            if (!$restaurant['meta']['success']) throw new Exception('Cannot find this restaurant', 404);
            $products = [];
            $grandTotal = 0;
            $grandTotalTax = 0;
            foreach ($request->input('cart') as $item) {
                if($item['type'] === 'menu') {
                    $product = $this->fetch('/restaurants/'. $request->input('restaurant') .'/menus/' . $item['id']);
                } else {
                    $product = $this->fetch('/restaurants/'. $request->input('restaurant') .'/products/' . $item['id']);
                }
                if (!$product['meta']['success']) throw new Exception('Cannot find this product', 404);

                if ($item['quantity'] <= 0) continue;
                $product['data']['quantity'] = (int)$item['quantity'];
                $product['data']['type'] = $item['type'];
                $products[] = $product['data'];
                $grandTotal += $product['data']['amount'] * $product['data']['quantity'];
            }

            $paypalRequest = new OrdersCreateRequest();
            $paypalRequest->prefer('return=representation');
            $paypalRequest->body = [
                'intent' => 'CAPTURE',
                'application_context' => [
                    'brand_name' => 'GoodFood',
                    'locale' => 'fr-FR',
                    'landing_page' => 'BILLING',
                    'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                    'user_action' => 'PAY_NOW'
                ],
                'purchase_units' => [
                    [
                        'description' => 'GoodFood',
                        'custom_id' => 'CUST-HighFashions',
                        'soft_descriptor' => 'HighFashions',
                        'amount' => [
                            'currency_code' => 'EUR',
                            'value' => round($grandTotal, 2),
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => 'EUR',
                                    'value' => round($grandTotal, 2),
                                ],
                                'shipping' => [
                                    'currency_code' => 'EUR',
                                    'value' => '0.00',
                                ],
                                'tax_total' => [
                                    'currency_code' => 'EUR',
                                    'value' => '0.00',
                                ]
                            ]
                        ],
                        'shipping' => [
                            'method' => 'Click and collect',
                            'address' => [
                                'address_line_1' => 'Boutique GoodFood',
                                'address_line_2' => '11 rue Saint Dizier',
                                'postal_code' => '54000',
                                'admin_area_2' => 'Nancy',
                                'country_code' => 'FR',
                            ],
                        ],
                    ],
                ],
            ];
            $client = PaypalClient::client();
            $paypalResponse = $client->execute($paypalRequest);
            $order = new Order();
            $order->user_id = $request->token->get('sub')->getValue();
            $order->paid = false;
            $order->status = 1;
            $order->restaurant_id = $request->input('restaurant');
            $order->paypal_id = $paypalResponse->result->id;
            $order->save();

            foreach ($products as $product) {
                if($product['type'] === 'menu') {
                    $row = new OrdersMenus([
                        'menu_id' => $product['id'],
                        'amount_untaxed' => $product['amount'],
                        'quantity' => $product['quantity']
                    ]);
                    $order->menus()->save($row);
                } else {
                    $row = new OrdersProducts([
                        'product_id' => $product['id'],
                        'amount_untaxed' => $product['amount'],
                        'quantity' => $product['quantity']
                    ]);
                    $order->products()->save($row);
                }
            }
            return $this->success($paypalResponse, 'Payment initiated');
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function capture(Request $request) {
        try {
            $this->validate($request, [
                'order' => 'required',
            ]);
            $paypalRequest = new OrdersCaptureRequest($request->input('order'));

            $client = PayPalClient::client();
            $paypalResponse = $client->execute($paypalRequest);
            if ($paypalResponse->result->status !== 'COMPLETED') throw new Exception('Payment not yet completed');

            $order = Order::where('paypal_id', $request->input('order'))->firstOrFail();
            $order->paid = true;
            $order->status = 2;
            $order->save();

            Mail::to($order->user->email)->send(new OrderConfirmed($order->user, $order));

            return $this->success($paypalResponse, 'Payment captured and order created', 201);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
