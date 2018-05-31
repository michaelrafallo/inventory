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
<title>Statement of Account</title>
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
        <td width="10%">TO</td>
        <td>: {{ $info->post_title }}</td>
    </tr>
    <tr>
        <td>DATE</td>
        <td>: {{ date('m-d-Y') }}</td>
    </tr>
</table>


<p>Dear {{ $info->contact_person }},</p>

<p>Listed below is the detail of your accounts payable. Thank you for your promt payment.</p>

<?php 

$search['post_id']     = Input::get('soid');
$search['post_status'] = Input::get('post_status', 'paid|!=');

$rows = $post->search($search, [], [])
             ->where('post_type', 'sales-order')
             ->where('parent', $info->id)
             ->orderBy('id', 'DESC')
             ->get(); 

$credit_balance = 0;

?>

@foreach($rows as $row)
<?php $postmeta = get_meta( $row->postMetas()->get() ); ?>

    @if($postmeta->orders)

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td width="78">Reference No. </td>
            <td> : {{ @$postmeta->si_no ? $postmeta->si_no : $postmeta->reference_no }}</td>
            <td align="right" colspan="2"> Date : {{ date_formatted($postmeta->date) }}</td>

        </tr>
        @if(@$postmeta->due_date)
        <tr>
            <td>Due Date</td>
            <td colspan="3"> : {{ date_formatted($postmeta->due_date) }}</td>
        </tr>
        @endif
    </table>

    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="margin-top:5px;">
    <thead>
        <tr>
            <th>Particular</th>
            <th width="10%" align="center">QTY</th>
            <th width="15%" align="right">Total</th>
        </tr>
    </thead>
    @foreach(json_decode($postmeta->orders) as $order)
    <tr>
        <td>{{ $post->find($order->item)->post_title }}</td>
        <td align="center">{{ number_format($order->quantity) }}</td>
        <td align="right">{{ number_format($order->price, 2) }}</td>
    </tr>
    @endforeach  
    <tr>
        <td>           
            <small>BALANCE</small> : {{ number_format($postmeta->balance, 2) }}               
        </td>
        <td align="right">
            <small>TOTAL</small>
        </td>
        <td align="right">
           {{ number_format($postmeta->total, 2) }}          
        </td>
    </tr>
    </table>      
    @endif

    <br>
<?php $credit_balance += $postmeta->balance; ?>
@endforeach

<h3>TOTAL BALANCE : {{ number_format($credit_balance, 2) }}</h3>

<br>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
       <td valign="top">
            <small>PREPARED BY :</small><br><br><br>
            <center><p>{{ Auth::User()->fullname }}</p></center>
       </td>
        <td valign="top"><small>RECEIVED BY</small> <br><br><br><br></td>
        <td valign="top"><small>DATE RECEIVED</small> <br><br><br><br></td>
    </tr>
</table>

<br><br>
<center>
<div style="border-top: 1px dashed;margin:20px 0;">
<h4>{{ App\Setting::get_setting('copy_right') }}</h4>
</div>
</center>

</body>