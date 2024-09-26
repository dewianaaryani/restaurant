<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
            <h1 class="mb-5">Our Best Menu</h1>
        </div>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
            <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                @foreach ($products->groupBy('category') as $category => $items)
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 {{ $loop->first ? 'active' : '' }} pb-3" data-bs-toggle="pill" href="#tab-{{ $loop->index + 1 }}">
                            <i class="fa {{ $loop->first ? 'fa-birthday-cake' : ($loop->index == 1 ? 'fa-hamburger' : 'fa-coffee') }} fa-2x text-primary"></i>
                            <div class="ps-3">
                                <small class="text-body">{{ ucfirst($category) }}</small>
                                <h6 class="mt-n1 mb-0">{{ ucfirst($category) }}</h6>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach ($products->groupBy('category') as $category => $items)
                    <div id="tab-{{ $loop->index + 1 }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }} p-0">
                        <div class="row g-4">
                            @foreach ($items as $product)
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center">
                                    <img class="flex-shrink-0 img-fluid rounded" src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 80px;">
                                    <div class="w-100 d-flex flex-column text-start ps-4">
                                        <h5 class="d-flex justify-content-between border-bottom pb-2">
                                            <span>{{ $product->name }}</span>

                                            <!-- Add to Cart Form without Quantity Input -->
                                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $product->stock->quantity == 0 ? 'btn-secondary' : 'btn-primary' }}" {{ $product->stock->quantity == 0 ? 'disabled' : '' }}>
                                                    {{ $product->stock->quantity == 0 ? 'Out of Stock' : 'Add to Cart' }}
                                                </button>
                                                <input type="hidden" name="quantity" value="1"> <!-- Set default quantity to 1 -->
                                            </form>
                                        </h5>
                                        <small class="fst-italic">Rp. {{ number_format($product->price, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
