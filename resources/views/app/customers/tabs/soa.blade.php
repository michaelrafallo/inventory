<?php $rows = $post->where('post_type', 'sales-order')
                   ->where('post_status', '<>', 'paid')
                   ->where('parent', $info->id)
                   ->orderBy('id', 'DESC')
                   ->get(); 

?>


<table class="table table-striped table-bordered">
    <tbody>
        @foreach($rows as $row)
        <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
        <tr>
            <td>
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="no-margin"><a href="{{ URL::route('app.sales-orders.edit', $row->id) }}" class="sbold">{{ $postmeta->reference_no }}</a></h4>
                        <small class="text-muted">ID : {{ $row->id }}</small><br>                    
                    </div>
                    <div class="col-md-6 text-right">

                        <div class="row">
                            <div class="col-md-8 text-right">
                                Date :        
                            </div>
                            <div class="col-md-4 text-left">
                                {{ date_formatted($postmeta->date) }} 
                            </div>   
                            @if(@$postmeta->due_date)
                            <div class="col-md-8 text-right">
                                Due Date :        
                            </div>
                            <div class="col-md-4 text-left">
                                {{ date_formatted($postmeta->due_date) }}
                            </div>       
                             @endif
                        </div>

                    </div>                    
                </div>
                
                @if($postmeta->orders)
                <table class="table table-striped table-condensed table-bordered margin-top-10">
                <thead>
                    <tr>
                        <th>Particular</th>
                        <th class="text-right">QTY</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                @foreach(json_decode($postmeta->orders) as $order)
                <tr>
                    <td>{{ @$post->find($order->item)->post_title }}</td>
                    <td align="right">{{ number_format($order->quantity) }}</td>
                    <td align="right">{{ amount_formatted($order->price) }}</td>
                </tr>
                @endforeach  
                </table>      
                @endif

                <div class="row">
                    <div class="col-md-9 text-right"><span class="text-muted">TOTAL</span> : </div>
                    <div class="col-md-3 text-right">{{ amount_formatted($postmeta->total) }}</div>                    
                </div>
                <div class="row">
                    <div class="col-md-9 text-right"><span class="text-muted">PAID</span> : </div>
                    <div class="col-md-3 text-right">{{ amount_formatted($postmeta->total_paid) }}</div>                    
                </div>
                <div class="row">
                    <div class="col-md-9 text-right text-danger sbold">BALANCE : </div>
                    <div class="col-md-3 text-right text-danger sbold">{{ amount_formatted($postmeta->balance) }}</div>                    
                </div>
     
            </td>

        </tr>
        @endforeach
    </tbody>   
</table>




