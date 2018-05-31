<?php $rows = $post->where('post_type', 'sales-order')->where('parent', $info->id)->orderBy('id', 'DESC')->get(); ?>


    <table class="table table-hover table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference No.</th>
                <th>Date</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            <tr>
                <td class="text-muted">{{ $row->id }}</td>
                <td> 
                    {{ $postmeta->reference_no }}<br>
                </td>
                <td>
                    {{ date_formatted(date_formatted_b($postmeta->date)) }}<br>
                    <small class="text-muted">{{ time_ago(date_formatted_b($postmeta->date)) }}</small>
                </td>
                <td class="text-right">{{ amount_formatted($postmeta->total) }}</td>
                <td class="text-right">{{ amount_formatted($postmeta->total_paid) }}</td>
                <td class="text-right">{{ amount_formatted($postmeta->balance) }}</td>
                <td>{{ status_ico($postmeta->delivered) }}<br> {{ status_ico($row->post_status) }}</td>
                <td>
                    <div class="text-right">                    
                        <a href="{{ URL::route('app.sales-orders.edit', $row->id) }}" class="btn green btn-xs uppercase">Edit</a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>      
</table>




