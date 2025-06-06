<?php
use App\Models\CategoryProduct;

$htmlSelect = '';

if (!function_exists('categoryRecursive')) {
    function categoryRecursive($id, $text = '', $htmlSelect)
    {
        $category = CategoryProduct::all();
        foreach ($category as $value) {
            if ($value->parent == $id) {
                $htmlSelect .= '<li>';
                $htmlSelect .= "<a href='" . route('home', $value->id) . "' title=''>" . $value->name . '</a>';
                if (isset($value->id)) {
                    $htmlSelect .= '<ul class="sub-menu">';
                    $htmlSelect = categoryRecursive($value->id, $text . '--', $htmlSelect);
                    $htmlSelect .= '</ul>';
                }
                $htmlSelect .= '</li>';
            }
        }
        return $htmlSelect;
    }
}

$htmlSelect = categoryRecursive(0, '', $htmlSelect);
?>
<div class="sidebar fl-left">
    <div class="section" id="category-product-wp">
        <div class="section-head">
            <h3 class="section-title">Danh mục sản phẩm</h3>
        </div>
        <div class="secion-detail">
            <ul class="list-item">
                <?php echo $htmlSelect; ?>
            </ul>
        </div>
    </div>
    <div class="section" id="selling-wp">
        <div class="section-head">
            <h3 class="section-title">Sản phẩm bán chạy</h3>
        </div>
        <div class="section-detail">
            <ul class="list-item">
                {{-- @foreach ($products as $product)
                        <li class="clearfix">
                            <a href="{{ route('detail_product', $product->product_id) }}" title=""
                                class="thumb fl-left">
                                <img src="{{ asset($product->thumbnail) }}" alt="">
                            </a>
                            <div class="info fl-right">
                                <a href="{{ route('detail_product', $product->product_id) }}" title=""
                                    class="product-name">{{ $product->product_name }}</a>
                                <div class="price">
                                    <span class="new">{{ number_format($product->price, 0, '.', ',') }}đ</span>
                                </div>
                                <a href="{{ route('detail_product', $product->product_id) }}" title=""
                                    class="buy-now">Mua ngay</a>
                            </div>
                        </li>
                    @endforeach --}}
            </ul>
        </div>
    </div>
</div>
