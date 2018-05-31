<style>
body {
    font-family: sans-serif;    
    font-size: 11px; 
}
</style>
<title>Purchase Order Register Report</title>
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
            <b>P.O. Register Report</b><br>
            {{ $date_from }} to {{ $date_to }}
        </td>
    </tr>
</table>

<br>

    <?php 
    $total = $paid = $balance = 0;
    ?>
    <table width="100%" cellpadding="3" cellspacing="0" border="1">
        <thead>
            <tr>
                <th>Reference No.</th>
                <th>Supplier</th>
                <th align="right">Total</th>
                <th align="right">Paid</th>
                <th align="right">Balance</th>
                <th align="center" width="45">Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            <tr>
                <td> 
                    {{ $postmeta->reference_no }}
                </td>
                <td>{{ $post->find($postmeta->supplier)->post_title }}</td>
                <td align="right">{{ number_format($postmeta->total, 2) }}</td>
                <td align="right">{{ number_format($postmeta->total_paid, 2) }}</td>
                <td align="right">{{ number_format($postmeta->balance, 2) }}</td>
                <td align="center">
                    {{ date_formatted_b($postmeta->date) }}
                </td>
                <td>{{ status_ico($postmeta->fulfilled) }}, {{ status_ico($row->post_status) }}</td>
            </tr>
            <?php 
                $total   += $postmeta->total;
                $paid    += $postmeta->total_paid;
                $balance += $postmeta->balance;

            ?>
            @endforeach
        </tbody>
    </table>     

    <br>

    <small>
    <table width="100%">
        <tr>
            <td width="60">TOTAL</td>
            <td>: {{ number_format($total, 2) }}</td>
        </tr>
        <tr>
            <td width="60">PAID</td>
            <td>: {{ number_format($paid, 2) }}</td>
        </tr>
        <tr>
            <td width="60">BALANCE</td>
            <td>: {{ number_format($balance, 2) }}</td>
        </tr>
    </table>
    </small>
</body>