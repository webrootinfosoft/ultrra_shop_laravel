<div class="stepwizard-row">
    <div class="stepwizard-step">
        <button class="btn btn-circle {{request()->is('www/products') ? 'btn-primary' : 'btn-light'}}" type="button">1</button>
        <p>@lang('cart.Select Products')</p>
    </div>
    @if(auth()->check())
    <div class="stepwizard-step">
        <button class="btn btn-circle {{request()->is('www/shipping-address') ? 'btn-primary' : 'btn-light'}}" type="button">2</button>
        <p>@lang('cart.Shipping Details')</p>
    </div>
    @else
    <div class="stepwizard-step">
        <button class="btn btn-circle {{request()->is('www/create-account') ? 'btn-primary' : 'btn-light'}}" type="button">2</button>
        <p>@lang('cart.Account Information')</p>
    </div>
    @endif
    <div class="stepwizard-step">
        <button class="btn btn-circle {{request()->is('www/review') ? 'btn-primary' : 'btn-light'}}" type="button">3</button>
        <p>@lang('cart.Review')</p>
    </div>
    <hr class="steps-hr"/>
</div>