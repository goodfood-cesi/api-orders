@component('mail::message')
    <center><img src="https://i.imgur.com/rlPBGQz.png" alt="Logo"></center>
<h1>
    Hello {{ $user->firstname }} !
</h1>
<p>
    Your order has been confirmed.
    Here is your order details:
</p>

@component('mail::table')
    | Order ID | Order Date | Order Status
    |:--------:|:----------:|:-----------:|
    | {{ $order->id }} | {{ $order->created_at }} | Paid |
@endcomponent


@component('mail::button', ['url' => 'http://ksu.li/account/orders'])
    VIEW ORDER DETAILS
@endcomponent

Thanks,
<br>
{{ config('app.name') }}
@endcomponent
