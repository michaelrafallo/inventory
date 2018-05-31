<style>
body {
    font-family: sans-serif;    
    font-size: 14px; 
}
.bordered {
    border: 1px solid;
}
.border-right {
    border-right: 1px solid;
}
.border-bottom {
    border-bottom: 1px solid;
}
.borderless-bottom {
    border-bottom: 0;
}

</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Deliver Receipt</title>
<body>

@if( @$company )
<center>
    <h3 style="margin:0;">{{ $company->post_title }}</h3>

    @if($company->address)
    {{ $company->address }}<br>
    @endif

    @if($company->telephone_number)
    Tel. No. : {{ $company->telephone_number }}<br>
    @endif

    @if($company->mobile_number)
    Cell No. : {{ $company->mobile_number }}<br>
    @endif

    @if($company->email_address)
    Email : {{ $company->email_address }}<br>
    @endif
</center>
@endif 

<p>To : {{ $post->find($info->customer)->post_title }}</p>
<p>Date : {{ date_formatted($info->date) }}</p>

<center>
<h3>DELIVERY RECEIPT NO. : <i>{{ @$info->dr_no ? $info->dr_no : $info->reference_no }}</i></h3>
</center>

<table width="100%" cellspacing="0" cellpadding="5" border="1">
    <thead>
    <tr>
        <th class="border-right border-bottom"><small>PARTICULAR</small></th>
        <th align="center" width="10%" class="border-right border-bottom"><small>QTY</small></th>
        <th align="center" width="10%" class="border-right border-bottom"><small>UNIT</small></th>
        @if(@$info->show_price)
        <th align="center" width="15%" class="border-right border-bottom"><small>UNIT PRICE</small></th>
        <th align="center" width="15%" class="border-bottom"><small>TOTAL PRICE</small></td>
        @endif
    </tr>        
    </thead>
    @if($info->orders)
    @foreach(json_decode($info->orders) as $order)
    <tr>
        <td class="border-bottom">{{ $post->find(@$order->item)->post_title }} {{ @$order->description ? ' - '.$order->description : '' }} </td>
        <td align="center" class="border-bottom">{{ number_format($order->quantity) }}</td>
        <td align="center" class="border-bottom">{{ @$order->unit_of_measure }}</td>
        @if(@$info->show_price)
        <td align="right" class="border-bottom">{{ number_format($order->price, 2) }}</td>
        <td align="right" class="border-bottom">{{ number_format($order->quantity * $order->price, 2) }}</td>
        @endif
    </tr>
    @endforeach 
    @endif
</table>

@if(@$info->show_total)
<p>TOTAL : {{ number_format($info->total, 2) }}</p>
@endif

<center>
<p>~~~~~~~ Nothing follows ~~~~~~~</p>

</center>

<pre>
Note: 
@if($info->delivery_address)
Delivery to {{ $info->delivery_address }}<br>
@endif
{{ $info->remarks }}
</pre>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
       <td valign="top" width="33%">
            <small>PREPARED BY :</small><br><br><br>
            <center><p>{{ Auth::User()->fullname }}</p></center>
       </td>
       <td valign="top" width="33%">
            <small>CHECKED BY :</small> <br><br><br>
       </td>
       <td valign="top" width="33%">
            <small>DELIVERED BY :</small> <br><br><br>
       </td>
    </tr>
    <tr>
        <td valign="top" colspan="2"><small>RECEIVED BY :</small> <br><br><br><br></td>
        <td valign="top"><small>DATE RECEIVED :</small> <br><br><br><br></td>
    </tr>
</table>

<br><br>
<center>
<div style="border-top: 1px dashed;margin:20px 0;">
<h4>{{ App\Setting::get_setting('copy_right') }}</h4>
</div>
</center>

</body>