@extends('layouts.master')

@section('content')
<style>
    .highlight {
    background-color: yellow;
    font-weight: bold;
    color: black;
}

</style>
<div class="container"> <br>
    <h1 style="font-size:18px; color:black;">Kết quả tìm kiếm cho: "{{ $query }}"</h1>

    @if ($products->isEmpty())
        <p>Không tìm thấy sản phẩm nào phù hợp.</p>
    @else
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm rounded border-2">
                        <figure class="block-4-image mb-0">
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                class="card-img-top" style="height: 200px; object-fit: cover; border-bottom: 2px solid #eee;">
                        </figure>
                        <div class="card-body text-center" style="padding-top: 10px;">
                            <h5 class="card-title" style="font-size: 16px; font-weight: 500; color: #333; margin-top: 5px;">
                                <a href="{{ route('product.show', $product->slug) }}" style="font-size: 16px; font-weight: bold; color: rgb(0, 0, 0); text-decoration: none;">
                                    {{-- Nổi bật từ khóa trong tên sản phẩm --}}
                                    {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="highlight">$1</span>', $product->name) !!}
                                </a>
                            </h5>
                            <p class="product-category text-muted" style="font-weight: 500; font-size: 14px; color: #777; margin-top: -5px;">
                                {{ $product->category->name }}
                            </p>
                            <p class="card-text" style="font-weight: 500; color: rgb(144, 29, 29); font-size: 18px; margin-top: 5px;">
                                {{ number_format($product->price) }}₫
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $products->links() }}
    @endif
</div>

@endsection
