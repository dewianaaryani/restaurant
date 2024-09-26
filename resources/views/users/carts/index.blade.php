@extends('.users.layouts.app')

@section('title', 'Carts')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Your Cart</h2>
        @if($carts->isEmpty())
            <div class="alert alert-warning" role="alert">
                Your cart is empty.
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Image</th> <!-- New Image Column -->
                        <th class="text-center align-middle">Product</th>
                        <th class="text-center align-middle">Quantity</th>
                        <th class="text-center align-middle">Price</th>
                        <th class="text-center align-middle">Total</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carts as $cart)
                    <tr>
                        <td class="text-center align-middle">
                            <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" style="width: 80px; height: auto;">
                        </td>
                        <td class="text-center align-middle">{{ $cart->product->name }}</td>
                        <td class="text-center align-middle">
                            <div class="input-group">
                                <button class="btn btn-outline-secondary btn-sm" onclick="changeQuantity({{ $cart->id }}, -1, {{ $cart->product->price }})">-</button>
                                <input type="number" class="form-control quantity-input" id="quantity-{{ $cart->id }}" value="{{ $cart->quantity }}" min="1" onchange="updateTotal({{ $cart->id }}, {{ $cart->product->price }})">
                                <button class="btn btn-outline-secondary btn-sm" onclick="changeQuantity({{ $cart->id }}, 1, {{ $cart->product->price }})">+</button>
                            </div>
                        </td>
                        <td class="text-center align-middle">Rp. {{ number_format($cart->product->price, 0, ',', '.') }}</td>
                        <td class="text-center align-middle" id="total-{{ $cart->id }}">Rp. {{ number_format($cart->quantity * $cart->product->price, 0, ',', '.') }}</td>
                        <td class="text-center align-middle">
                            <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">Overall Total:</td>
                        <td class="text-center" id="overall-total">Rp. {{ number_format($carts->sum(function ($cart) { return $cart->quantity * $cart->product->price; }), 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-right">
                <a href="{{route('checkout')}}" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        @endif
    </div>
@endsection

@section('script')
<script>
    document.querySelectorAll('.quantity-button').forEach(button => {
  button.addEventListener('click', event => {
    const cartId = event.target.dataset.cartId;
    const change = event.target.dataset.change;
    const price = event.target.dataset.price;
    changeQuantity(cartId, change, price);
  });
});

function changeQuantity(cartId, change, price) {
  const quantityInput = document.getElementById(`quantity-${cartId}`);
  if (quantityInput) {
    let quantity = parseInt(quantityInput.value) + change;

    // Prevent quantity from going below 1
    if (quantity < 1) {
      quantity = 1; // Set to minimum of 1
    }
    
    quantityInput.value = quantity; // Update the input field
    updateTotal(cartId, price); // Update the total for this specific cart item
  }
}

function updateTotal(cartId, price) {
  const quantityInput = document.getElementById(`quantity-${cartId}`);
  if (quantityInput) {
    const quantity = parseInt(quantityInput.value);
    const totalElement = document.getElementById(`total-${cartId}`);
    if (totalElement) {
      const total = quantity * price;
      totalElement.innerText = 'Rp. ' + total.toLocaleString('id-ID'); // Format as Indonesian currency

      // Update overall total
      updateOverallTotal(); // Call to update the overall total
    }
  }
}

function updateOverallTotal() {
  let overallTotal = 0;
  const cartRows = document.querySelectorAll('tbody tr');
  cartRows.forEach(row => {
    const itemTotal = parseInt(row.querySelector('[id^="total-"]').innerText.replace(/[^0-9]/g, '')); // Remove Rp. and format
    overallTotal += itemTotal;
  });

  document.getElementById('overall-total').innerText = 'Rp. ' + overallTotal.toLocaleString('id-ID'); // Update the overall total display
}
</script>
@endsection
