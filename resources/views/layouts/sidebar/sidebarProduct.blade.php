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
                $htmlSelect .= "<a href='" . route('list_product_client', $value->id) . "' title='' >" . $value->name . '</a>';
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

$categories = CategoryProduct::where('parent', 0)->get();
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

    <div class="section" id="filter-product-wp">
        <div class="section-head">
            <h3 class="section-title">Bộ lọc</h3>
        </div>
        <div class="section-detail">
            <form method="GET" action="{{ route('product_filter', $id) }}" id="price-filter-form">@csrf
                <table>
                    <tbody>
                        <tr>
                            <td><input id="price-1" type="radio" name="r-price" value="1" class="arrange-price"
                                    data-url="{{ route('product_filter') }}"></td>
                            <td><label for="price-1">Dưới 500.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-2" type="radio" name="r-price" value="2" class="arrange-price"
                                    data-url="{{ route('product_filter') }}"></td>
                            <td><label for="price-2">500.000đ - 1.000.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-3" type="radio" name="r-price" value="3" class="arrange-price"
                                    data-url="{{ route('product_filter') }}"></td>
                            <td><label for="price-3">1.000.000đ - 5.000.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-4" type="radio" name="r-price" value="4" class="arrange-price"
                                    data-url="{{ route('product_filter') }}"></td>
                            <td><label for="price-4">5.000.000đ - 10.000.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-5" type="radio" name="r-price" value="5" class="arrange-price"
                                    data-url="{{ route('product_filter') }}"></td>
                            <td><label for="price-5">10.000.000đ - 20.000.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-6" type="radio" name="r-price" value="6" class="arrange-price"
                                    data-url="{{ route('product_filter') }}"></td>
                            <td><label for="price-6">Trên 20.000.000đ</label></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
