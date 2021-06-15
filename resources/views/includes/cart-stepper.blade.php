<div class="stepwizard-row">
    <div class="stepwizard-step">
        <button class="btn btn-circle {{request()->is('www/products') ? 'btn-primary' : 'btn-light'}}" type="button">1</button>
        <p>Select Products</p>
    </div>
    @if(auth()->check())
    <div class="stepwizard-step">
        <button class="btn btn-circle {{request()->is('www/shipping-address') ? 'btn-primary' : 'btn-light'}}" type="button">2</button>
        <p>Shipping Details</p>
    </div>
    @else
    <div class="stepwizard-step">
        <button class="btn btn-circle {{request()->is('www/create-account') ? 'btn-primary' : 'btn-light'}}" type="button">2</button>
        <p>Account Information</p>
    </div>
    @endif
    <div class="stepwizard-step">
        <button class="btn btn-circle {{request()->is('www/review') ? 'btn-primary' : 'btn-light'}}" type="button">3</button>
        <p>Review</p>
    </div>
    <hr class="steps-hr"/>
</div>