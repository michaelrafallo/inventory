<style>
body {
    font-family: sans-serif;    
    font-size: 13px; 
}
</style>
<title>Inventory Register Report</title>
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
            <b>Inventory Register Report</b><br>
            {{ $date_from }} to {{ $date_to }}
        </td>
    </tr>
</table>

<br>

    <table width="100%" cellpadding="5" cellspacing="0" border="1">
        <thead>
            <tr>
                <th width="70" align="center">Reference No.</th>
                <th>Item Name</th>
                <th align="right" width="30">QTY</th>
                <th width="1" align="center">UoM</th>
                <th align="right" width="40">Price</th>
                <th align="center" width="60">Date</th> 
                <th width=""></th>                               
                <th width="1">Transaction</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td align="center">{{ $row->reference_no }}</td>
                <td>{{ $post->find($row->item)->post_title }}</td>
                <td align="right">{!! number_format($row->qty) !!}</td>
                <td align="center">{{ $row->uom }}</td>
                <td align="right">{{ number_format($row->price, 2) }}</td>
                <td align="center">{{ date_formatted($row->date) }}</td>
                <td>{{ $post->find($row->person_id)->post_title }}</td>
                <td align="center">{{ status_ico($row->type) }}</td>
            </tr>
            @endforeach

        </tbody>
    </table> 

</body>