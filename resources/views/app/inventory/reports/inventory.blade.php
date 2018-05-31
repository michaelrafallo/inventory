<style>
body {
    font-family: sans-serif;    
    font-size: 12px; 
}

</style>
<title>Inventory Report</title>
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
            <b>Inventory Report</b><br>
            {{ $date_from }} to {{ $date_to }}
        </td>
    </tr>
</table>

<br>

    <table width="100%" cellpadding="5" cellspacing="0" border="1">
        <thead>
            <tr>
                <th>Details</th>
                <th width="60" align="center">Date</th>
                <th>Remarks</th>
                <th width="50" align="center">IN</th>
                <th width="50" align="center">OUT</th>
                <th width="50" align="center">Balance</th>
                <th width="1" align="center">UoM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)

                <?php 
                    $postmeta = get_meta( $row->postMetas()->get() ); 

                    $orders   = $inventory->where('item', $row->id)->whereBetween('date', [$from, $to])->get();

                    $inv[$row->id] = $orders;
                    $rowspan = count($inv[$row->id]);
                    
                    $show = 0;

                    if( Input::get('empty') && $rowspan ) {
                        $show = 1;
                    } elseif( ! Input::get('empty') ) {
                        $show = 1;
                    }
                ?>

                @if( $show )

                <tr>
                    <td rowspan="{{ $rowspan }}" align="center">{{ $row->post_title }}</td>
                    <td align="center">{{ date_formatted(@$orders[0]->date) }}</td>
                    <td align="center">
                        @if( @$orders[0]->person_id )
                        {{ @$orders[0]->type == 'in' ? 'Purcahsed from ' : 'Sold to ' }} {{ $post->find($orders[0]->person_id)->post_title }}
                        @endif
                    </td>
                    <td align="center">{{ @$orders[0]->type == 'in' ? number_format(@$orders[0]->qty) : '' }}</td>
                    <td align="center">{{ @$orders[0]->type == 'out' ? number_format(@$orders[0]->qty) : '' }}</td>
                    <td rowspan="{{ $rowspan }}" align="center">{{ number_format($postmeta->quantity) }}</td>  
                    <td rowspan="{{ $rowspan }}" align="center">{{ metrics($postmeta->unit_of_measure) }}</td>
                </tr>

                <?php $o=1; ?>
                @foreach($orders as $order)
                @if($o != 1)
                <tr>
                    <td align="center">{{ date_formatted($order->date) }}</td>
                    <td align="center">{{ $order->type == 'in' ? 'Purcahsed from ' : 'Sold to ' }} {{ $post->find($order->person_id)->post_title }}</td>
                    <td align="center">{{ $order->type == 'in' ? number_format($order->qty) : '' }}</td>
                    <td align="center">{{ $order->type == 'out' ? number_format($order->qty) : '' }}</td>
                </tr>
                @endif
                <?php $o++; ?>
                @endforeach

                @endif

            @endforeach

        </tbody>
    </table> 

</body>