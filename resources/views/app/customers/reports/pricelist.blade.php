<style>
body {
    font-family: sans-serif;    
    font-size: 14px; 
}
.borderless-top, .borderless-top td {
    border-top: 0!important;
}
</style>
<title>Customer Pricelist</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<body>

<table width="100%">
    <tr>
        <td>
            @if( @$company )
                {{ $company->post_title }}<br>
                @if($company->email_address)
               {{ $company->email_address }}<br>
                @endif
                {{ $company->mobile_number }}
             @endif         
        </td>
        <td align="right" valign="top">
            <b>Pricelist</b><br>
            Date : {{ date('F d, Y') }}
        </td>
    </tr>
</table>

<br>

<?php 

parse_str( query_vars(), $search );

$customers = $post->search($search)
                     ->where('post_type', 'customer')
                     ->orderBy('post_title', 'ASC')
                     ->get();
?>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th width="100" align="center">Customer</th>
            <th>Item</th>
            <th align="right" width="100">Supplier Amount</th>
            <th align="right" width="100">Customer Amount</th>
        </tr>
    </thead>

@foreach($customers as $customer)

<?php 
$products = $post->select('posts.*', 
                       'm1.meta_value as customers'
                    )->from('posts')
                     ->join('postmeta AS m1', function ($join) use ($customer) {
                        $join->on('posts.id', '=', 'm1.post_id')
                             ->where('m1.meta_key', '=', 'customers')
                             ->where('m1.meta_value',  'LIKE', '%"customer":"'.$customer->id.'"%');
                    })->where('posts.post_type', 'product')
                      ->get();

?>

<?php $p=1;?>
@foreach($products as $product)
<?php 
    $postmeta = get_meta( $product->postMetas()->get() ); 
    $c = json_decode($postmeta->customers, true);
?>
<tr>
        @if($p==1)
    <td rowspan="{{ count($products) }}" width="100" align="center">
        {{ $customer->post_title }}
    </td>
        @endif
    <td>{{ $product->post_title }}</td>
    <td align="right" width="100">{{ number_format(@$c[$customer->id]['supplier_price'], 2) }}</td>
    <td align="right" width="100">{{ number_format(@$c[$customer->id]['customer_price'], 2) }}</td>
</tr>
<?php $p++; ?>
@endforeach


@endforeach

    </tbody>      
</table>

</body>