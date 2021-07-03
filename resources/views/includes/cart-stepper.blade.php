<div class="stepwizard-row">
    <div class="stepwizard-step">
        <a href="{{url('/www/products')}}" id="products-cart-navigator" class="btn btn-circle {{request()->is('www/products') ? 'btn-primary' : 'btn-light'}}" type="button">1</a>
        <p>@lang('cart.Select Products')</p>
    </div>
    @if(auth()->check())
    <div class="stepwizard-step">
        <a href="{{url('/www/shipping-address')}}" id="shipping-address-cart-navigator" class="btn btn-circle {{request()->is('www/shipping-address') ? 'btn-primary' : 'btn-light'}}" type="button">2</a>
        <p>@lang('cart.Shipping Details')</p>
    </div>
    @else
    <div class="stepwizard-step">
        <a href="{{url('/www/create-account')}}" id="create-account-cart-navigator" class="btn btn-circle {{request()->is('www/create-account') ? 'btn-primary' : 'btn-light'}}" type="button">2</a>
        <p>@lang('cart.Account Information')</p>
    </div>
    @endif
    <div class="stepwizard-step">
        <a href="{{url('/www/review')}}" id="review-cart-navigator" class="btn btn-circle {{request()->is('www/review') ? 'btn-primary' : 'btn-light'}}" type="button">3</a>
        <p>@lang('cart.Review')</p>
    </div>
    <hr class="steps-hr"/>
</div>
<style>
    .stepwizard-step p
    {
        font-size: 14px;
    }
</style>