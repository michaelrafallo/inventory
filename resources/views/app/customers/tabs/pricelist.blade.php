<?php 
$products = $post->select('posts.*', 
                       'm1.meta_value as customers'
                    )->from('posts')
                     ->join('postmeta AS m1', function ($join) use ($info) {
                        $join->on('posts.id', '=', 'm1.post_id')
                             ->where('m1.meta_key', '=', 'customers')
                             ->where('m1.meta_value',  'LIKE', '%"customer":"'.$info->id.'"%');
                    })->where('posts.post_type', 'product')
                      ->get();

?>


<table class="table table-striped table-hover table-bordered datatable">
    <thead>
        <tr>
            <th>Item</th>
            <th class="text-right">Supplier Amount</th>
            <th class="text-right">Customer Amount</th>
            <th class="text-right">Stocks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <?php 
            $postmeta = get_meta( $product->postMetas()->get() ); 
            $customer = json_decode($postmeta->customers, true);
        ?>
        <tr>
            <td>
                <h5 class="no-margin"><a href="{{ URL::route('app.inventory.edit', [$product->id, 'tab' => 1]) }}" class="sbold">{{ $product->post_title }}</a></h5>
                <small class="text-muted">ID : {{ $product->id }}</small><br>

                <a href="{{ URL::route($view.'.product', ['pid' => $product->id, 'sid' => $info->id]) }}" class="btn green btn-xs uppercase margin-top-10 btn-edit">Edit</a> 

                <a href="#" class="popup btn btn-xs btn-default uppercase margin-top-10"
                    data-href="{{ URL::route($view.'.delete', [$product->id, query_vars()]) }}" 
                    data-toggle="modal"
                    data-target=".popup-modal" 
                    data-title="Confirm Delete"
                    data-body="Are you sure you want to move to trash ID: <b>#{{ $product->id }}</b>?">Move to trash</a> 
            </td>
            <td class="text-right">{{ amount_formatted($customer[$info->id]['supplier_price']) }}</td>
            <td class="text-right">{{ amount_formatted(@$customer[$info->id]['customer_price']) }}</td>
            <td class="text-right stocks">{!! stock_qty(@$postmeta->quantity) !!}</td>
        </tr>
        @endforeach
    </tbody>      
</table>




