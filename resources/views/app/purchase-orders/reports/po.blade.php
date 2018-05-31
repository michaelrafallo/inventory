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
<title>Purcahse Order</title>
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

<table width="100%">
    <tr>
        <td><h3 style="margin:8px 0;">PURCHASE ORDER</h3></td>
        <td align="right">{{ @$info->po_no ? $info->po_no : $info->reference_no }}</td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="5" class="bordered borderless-bottom">
    <tr>
        <td width="10%">Supplier</td>
        <td class="border-right">: {{ $post->find($info->supplier)->post_title }}</td>
        <td width="5%">Date</td>
        <td width="18%">: {{ date_formatted($info->date) }}</td>
    </tr>
    <tr>
        <td>Address</td>
        <td  class="border-right">: {{ $info->address }}</td>
        <td>Terms</td>
        <td>: {{ $info->terms }}</td>
    </tr>    
</table>


<table width="100%" cellspacing="0" cellpadding="5" class="bordered borderless-bottom">
    <thead>
    <tr>
        <th align="center" width="10%" class="border-right border-bottom"><small>QTY</small></th>
        <th align="center" width="10%" class="border-right border-bottom"><small>UNIT</small></th>
        <th class="border-right border-bottom"><small>ITEM / DESCRIPTION</small></th>
        <th align="center" width="15%" class="border-right border-bottom"><small>UNIT PRICE</small></th>
        <th align="center" width="15%" class="border-bottom"><small>TOTAL PRICE</small></th>
    </tr>        
    </thead>
    @if($info->orders)
    @foreach(json_decode($info->orders) as $order)
    <tr>
        <td align="center" class="border-right border-bottom">{{ number_format($order->quantity) }}</td>
        <td align="center" class="border-right border-bottom">{{ @$order->unit_of_measure }}</td>
        <td class="border-right border-bottom">{{ $post->find(@$order->item)->post_title }} {{ @$order->description ? ' - '.$order->description : '' }} </td>
        <td align="right" class="border-right border-bottom">{{ number_format($order->price, 2) }}</td>
        <td align="right" class="border-right  border-bottom">{{ number_format($order->quantity * $order->price, 2) }}</td>
    </tr>
    @endforeach 
    @endif
</table>


<br>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
       <td valign="top" width="33%">
            <small>ISSUED BY :</small> <br><br><br>
       </td>
       <td valign="top" width="33%">
            <small>APPROVED BY :</small> <br><br><br>
       </td>
       <td valign="top" width="33%">
            <small>PREPARED BY :</small><br><br><br>
            <center><p>{{ Auth::User()->fullname }}</p></center>
       </td>
    </tr>
</table>

</body>