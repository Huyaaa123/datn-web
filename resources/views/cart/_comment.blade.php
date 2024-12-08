<div class="product-detail"
    style="font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">

    <div class="product-rating-filter"
        style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
        <!-- Đánh giá sản phẩm -->
        <div class="product-rating"
            style="display: flex; align-items: center; justify-content: center; margin-left: 20px; text-align: left;">
            <div style="font-size: 28px; font-weight: bold; color: #ee2626;">
                {{ number_format($product->comments->avg('rating'), 1) }} / 5
            </div>
        </div>
        <div class="rating-stars" style="display: inline-block;">
            @php
                $averageRating = $product->comments->avg('rating');
                $fullStars = floor($averageRating); // Số sao đầy
                $fractionalStar = $averageRating - $fullStars; // Phần sao lẻ
            @endphp

            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $fullStars)
                    <span style="font-size: 32px; color: gold;">★</span> <!-- Hiển thị sao đầy -->
                @elseif ($i == $fullStars + 1 && $fractionalStar > 0)
                    <span
                        style="font-size: 32px; color: gold; background: linear-gradient(90deg, gold {{ $fractionalStar * 100 }}%, gray {{ $fractionalStar * 100 }}%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">★</span>
                    <!-- Hiển thị sao một phần -->
                @else
                    <span style="font-size: 32px; color: gray;">★</span> <!-- Hiển thị sao rỗng -->
                @endif
            @endfor
        </div>

        <div class="rating-filter" style="display: flex; margin-right: 150px; gap: 10px;">
            <button
                style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                <a href="{{ route('product.show', $product->slug) }}" style="text-decoration: none; color: #333;">
                    Tất Cả ({{ $product->comments->count() }})
                </a>
            </button>
            <button
                style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                <a href="{{ route('product.show', $product->slug) }}?rating=5"
                    style="text-decoration: none; color: #333;">
                    5 Sao ({{ $product->comments->where('rating', 5)->count() }})
                </a>
            </button>
            <button
                style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                <a href="{{ route('product.show', $product->slug) }}?rating=4"
                    style="text-decoration: none; color: #333;">
                    4 Sao ({{ $product->comments->where('rating', 4)->count() }})
                </a>
            </button>
            <button
                style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                <a href="{{ route('product.show', $product->slug) }}?rating=3"
                    style="text-decoration: none; color: #333;">
                    3 Sao ({{ $product->comments->where('rating', 3)->count() }})
                </a>
            </button>
            <button
                style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                <a href="{{ route('product.show', $product->slug) }}?rating=2"
                    style="text-decoration: none; color: #333;">
                    2 Sao ({{ $product->comments->where('rating', 2)->count() }})
                </a>
            </button>
            <button
                style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                <a href="{{ route('product.show', $product->slug) }}?rating=1"
                    style="text-decoration: none; color: #333;">
                    1 Sao ({{ $product->comments->where('rating', 1)->count() }})
                </a>
            </button>
        </div>
    </div>

    <!-- Hiển thị bình luận -->
    <div class="comments-container" style="margin-top: 30px; padding: 20px; border-radius: 10px;">

        @if ($comments->isEmpty()) <!-- Kiểm tra nếu không có bình luận -->
            <div class="no-comments"
                style="padding: 15px; text-align: center; font-size: 16px; color: #888; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 10px;">
                Chưa có bình luận nào cho sản phẩm này.
            </div>
        @else
            @foreach ($comments as $comment)
                <div class="comment"
                    style="margin-top: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;">
                    <div class="comment-header">
                        <strong style="color: black">{{ $comment->user->name }}</strong> -
                        <span style="font-size: 14px; color: gray;">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="comment-rating" style="margin-bottom: 10px;">
                        @for ($i = 1; $i <= 5; $i++)
                            <span
                                style="font-size: 15px; color: {{ $i <= $comment->rating ? 'gold' : 'gray' }};">★</span>
                        @endfor
                    </div>
                    <p style="margin: 10px 0; color: #333;">{{ $comment->content }}</p>
                </div>
            @endforeach
        @endif
    </div>
    <div class="add-comment-form" style="margin-top: 30px;">
        @if (session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        @php
            // Kiểm tra số lần người dùng đã mua sản phẩm này
            $purchasedCount = \App\Models\OrderDetail::whereHas('order', function($query) {
                $query->where('user_id', auth()->id())
                      ->where('order_status_id', 6); // Trạng thái đơn hàng thành công
            })
            ->where('product_id', $product->id) // Sản phẩm cụ thể
            ->count();

            // Kiểm tra số lần người dùng đã bình luận sản phẩm này
            $commentedCount = \App\Models\Comment::where('user_id', auth()->id())
                                                ->where('product_id', $product->id)
                                                ->count();
        @endphp

        @if ($purchasedCount > $commentedCount)  <!-- Nếu số lần mua lớn hơn số lần bình luận -->
            <form action="{{ route('product.show', $product->slug) }}" method="POST" style="margin-top: 15px;">
                @csrf
                <div style="font-size:24px;margin-bottom: 15px;">
                    <label for="rating" style="font-weight: bold; color: #333;">Đánh giá:</label>
                    <div class="star-rating" style="display: flex; justify-content: left; cursor: pointer;">
                        <span class="star" data-value="1" style="font-size: 32px; color: gray;">★</span>
                        <span class="star" data-value="2" style="font-size: 32px; color: gray;">★</span>
                        <span class="star" data-value="3" style="font-size: 32px; color: gray;">★</span>
                        <span class="star" data-value="4" style="font-size: 32px; color: gray;">★</span>
                        <span class="star" data-value="5" style="font-size: 32px; color: gray;">★</span>
                    </div>
                    <input type="hidden" name="rating" id="rating" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <textarea name="content" id="content" rows="4" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                </div>
                <button type="submit"
                    style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    Gửi bình luận
                </button>
            </form>
        @elseif ($commentedCount > 0)

        @else

        @endif
    </div>

</div>
<br>
<script>
    // JavaScript để thay đổi màu sao khi click và gửi giá trị vào input hidden
    document.querySelectorAll('.star').forEach(function(star) {
        star.addEventListener('click', function() {
            var rating = this.getAttribute('data-value'); // Lấy giá trị của sao đã click
            document.getElementById('rating').value = rating; // Gửi giá trị vào input hidden
            updateStarColors(rating); // Cập nhật màu sắc sao
        });
    });

    function updateStarColors(rating) {
        document.querySelectorAll('.star').forEach(function(star) {
            var starValue = star.getAttribute('data-value');
            if (starValue <= rating) {
                star.style.color = 'gold'; // Màu vàng cho sao đã chọn
            } else {
                star.style.color = 'gray'; // Màu xám cho sao chưa chọn
            }
        });
    }
</script>
