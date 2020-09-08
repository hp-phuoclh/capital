<table class="table table-bordered table-hover products">
    <thead>
        <tr>
            <th></th>
            <th>{{ __('Image') }}</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Product code') }}</th>
            <th>{{ __('Category') }}</th>
            <th>{{ __('Price') }}</th>
            <th>{{ __('Discount') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td>
                {{ $product->id }}
            </td>
            <td>
                <img src="{{ asset($product->image) }}" class="img-fluid" alt="Image" width="100">
            </td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->code }}</td>
            <td>{{ $product->category ? $product->category->name : ''}}</td>
            <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
            <td>{{ number_format($product->sale_off, 0, ',', '.') }} ₫</td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    var json_products = @json($products)
</script>