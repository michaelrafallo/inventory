<?php 

$rows = $post->where('post_type', 'purchase-order')
                   ->where('post_content', 'LIKE' , '%"item":"'.$info->id.'"%')
                   ->orderBy('id', 'DESC')->get(); ?>



    <table class="table table-hover table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference No.</th>
                <th>Supplier</th>
                <th>QTY</th>
                <th>Unit Price</th>
                <th>Sub-total</th>
                <th>Date Ordered</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <?php 
                $postmeta = get_meta( $row->postMetas()->get() ); 
                $order    = json_decode($postmeta->orders, true);
            ?>
            <tr>
                <td class="text-muted">{{ $row->id }}</td>
                <td><a href="{{ URL::route('app.purchase-orders.edit', $row->id) }}" target="_blank">{{ $postmeta->reference_no }}</a></td>
                <td>{{ $post->find($postmeta->supplier)->post_title }}</td>
                <td align="right">{{ number_format(@$order[$info->id]['quantity']) }}</td>
                <td align="right">{{ amount_formatted(@$order[$info->id]['price']) }}</td>
                <td class="text-right">{{ amount_formatted(@$order[$info->id]['sub_total']) }}</td>
                <td>
                    {{ date_formatted(date_formatted_b($postmeta->date)) }}<br>
                    <small class="text-muted">{{ time_ago(date_formatted_b($postmeta->date)) }}</small>
                </td>
                <td>{{ status_ico($postmeta->fulfilled) }}<br> {{ status_ico($row->post_status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>      
</table>




