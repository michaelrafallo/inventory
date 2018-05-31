@if($orders = json_decode($info->orders))
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Particulars</th>
            <th class="text-right">QTY</th>
            <th width="1">UoM</th>
            <th class="text-right">Price</th>
            <th class="text-right">Sub-Total</th>
        </tr>
    </thead>
    @foreach( $orders as $order)
    <tr>
        <td width="50%">

            <a href="{{ URL::route('app.inventory.edit', [$order->item, 'tab' => 1]) }}">{{ @$post->find($order->item)->post_title }}</a>

            @if($order->description)
            - {{ $order->description }}
            @endif

            @if($order->code)
            <br><small class="text-muted">SKU : {{ $order->code }}</small>
            @endif
        </td>
        <td class="text-right">{{ number_format($order->quantity) }}</td>
        <td>{{ @$order->unit_of_measure }}</td>
        <td class="text-right">{{ amount_formatted($order->price) }}</td>
        <td class="text-right">{{ amount_formatted($order->sub_total) }}</td>
    </tr>
    @endforeach
</table>
@endif
