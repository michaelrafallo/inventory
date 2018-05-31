<style>
body {
    font-family: sans-serif;    
    font-size: 12px; 
}
</style>
<title>Financial Expenses Report</title>
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
            <b>Finacial Expenses Report</b><br>
            {{ $date_from }} to {{ $date_to }}
        </td>
    </tr>
</table>

<br>


<table width="100%" cellspacing="0" cellpadding="3" border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th align="right">Amount</th>
            <th align="center">Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
        <tr>
            <td>{{ $row->post_title }}</td>
            <td align="right">{{ number_format($postmeta->amount, 2) }}</td>
            <td align="center">{{ date_formatted_b($postmeta->date) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>   

<p>TOTAL : {{ number_format($total_exp, 2) }}</p>

</body>