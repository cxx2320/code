<?php

/*
 * This file is part of the mingzaily/lumen-permission.
 *
 * (c) mingzaily <mingzaily@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class OrderPayEvent implements \SplSubject
{
    private $observers;
    public $order;

    public function __construct(Order $order)
    {
        $this->order     = $order;
        $this->observers = new \SplObjectStorage();
    }

    public function attach(\SplObserver $observer) //加入观察者
    {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    public function notify() //通知所有观察者  也就是执行所有观察者
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}

class UpdateOrderStatus implements \SplObserver
{
    public function update(SplSubject $subject)
    {
        $subject->order->status = 'success';
        echo '订单状态更改成功' . PHP_EOL;
    }
}

class SmsNotify implements \SplObserver
{
    public function update(SplSubject $subject)
    {
        echo '发送短信:' . $subject->order->user_id . '购买了商品' . PHP_EOL;
    }
}

class Order
{
    public $status  = 'fail';
    public $user_id = '1';
}

$order = new Order();

$event = new OrderPayEvent($order);
//$event->attach(new UpdateOrderStatus());
//$event->attach(new SmsNotify());
$event->notify();
