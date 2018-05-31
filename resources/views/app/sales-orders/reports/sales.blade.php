<style>
body {
    font-family: sans-serif;    
    font-size: 11px; 
}
</style>
<title>Sales Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<body>

<table width="100%">
    <tr>
        <td>
            @if( @$info )
                {{ $info->post_title }}<br>
                @if($info->email_address)
               {{ $info->email_address }}<br>
                @endif
                {{ $info->mobile_number }}
             @endif         
        </td>
        <td align="right" valign="top">
            <b>Sales Report</b><br>
            {{ $date_from }} to {{ $date_to }}
        </td>
    </tr>
</table>

<br>

    <?php 
    $total_paid = 0;
    ?>
    <table width="100%" cellpadding="3" cellspacing="0" border="1">
        <thead>
            <tr>
                <th align="center" width="50">Date</th>
                <th align="center">Supplier</th>
                <th>Details</th>
                <th width="65" align="center">Reference No.</th>                
                <th align="right" width="60">Total</th>
                <th align="right" width="60">Paid</th>
                <th align="right" width="60">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            <tr>
                <td align="center">{{ date_formatted_b($postmeta->date) }}</td>
                <td align="center">{{ $post->find($postmeta->customer)->post_title }}</td>
                <td>
                    @if( is_array(json_decode($postmeta->orders, true)) )

                        @foreach(json_decode($postmeta->orders) as $order)
                        <div style="margin-bottom:3px;">
                        {{ @$post->find($order->item)->post_title }}<br>
                        <small style="color:#6f6e6e;">{{ number_format(@$order->quantity) }} x {{ number_format(@$order->price, 2) }}</small>
                        </div>
                        @endforeach    

                    @endif           
                </td>
                <td align="center">{{ $postmeta->reference_no }}</td>
                <td align="right">{{ number_format($postmeta->total, 2) }}</td>
                <td align="right">{{ number_format($postmeta->total_paid, 2) }}</td>
                <td align="right">{{ number_format($postmeta->balance, 2) }}</td>
            </tr>
            <?php 
                $total_paid    += $postmeta->total_paid;
            ?>
            @endforeach
        </tbody>
    </table>     

    <br>

    <small>
    <table width="100%">
        <tr>
            <td width="60">TOTAL SALES</td>
            <td><b>: {{ number_format($total_paid, 2) }}</b></td>
        </tr>
    </table>
    </small>
</body>