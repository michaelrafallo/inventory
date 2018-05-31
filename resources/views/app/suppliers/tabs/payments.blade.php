<?php $rows = $post->where('post_type', 'purchase-order')->where('parent', $info->id)->orderBy('id', 'DESC')->get(); ?>


    <table class="table table-hover table-striped datatable">
        <thead>
            <tr>
                <th>Order No.</th>
                <th>Date</th>
                <th>Reference No.</th>
                <th>Method</th>
                <th>Total</th>
                <th>Amount Paid</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            @if($postmeta->payments)
                @foreach(json_decode($postmeta->payments) as $payment)
                <tr>
                    <td>{{ $postmeta->reference_no }}</td>
                    <td>
                        @if($payment->date)
                        {{ date_formatted(date_formatted_b($payment->date)) }}<br>
                        <small class="text-muted">{{ time_ago(date_formatted_b($payment->date)) }}</small>
                        @endif
                    </td>
                    <td>{{ $payment->reference_no }}</td>
                    <td>
                    @if($payment->method)
                    {{ payment_method($payment->method) }}
                    @endif
                    </td>
                    <td class="text-right">{{ amount_formatted($postmeta->total) }}</td>
                    <td class="text-right">{{ amount_formatted($payment->amount) }}</td>
                    <td>
                        <div class="text-right">                    
                            <a href="{{ URL::route('app.purchase-orders.edit', $row->id) }}" class="btn green btn-xs uppercase" target="_blank">View</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            @endif
            @endforeach
        </tbody>
    </table>      
</table>




