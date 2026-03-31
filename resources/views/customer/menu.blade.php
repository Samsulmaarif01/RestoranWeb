@extends('customer.layouts.master')

@section('content')
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-3">
                    <div class="col-lg">
                        <div class="row g-4 justify-content-center">

                            @foreach($items as $item)
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded position-relative fruite-item">
                                    <div class="fruite-img">
                                        <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('img_item_upload/' . $item->image) }}" 
                                             class="img-fluid w-100 rounded-top" alt="{{ $item->name }}" 
                                             onerror="this.onerror=null;this.src='{{ $item->image }}';">
                                    </div>

                                    <div class="text-white px-3 py-1 rounded position-absolute 
                                        {{ $item->category->cat_name == 'Makanan' ? 'bg-warning' : ($item->category->cat_name == 'Minuman' ? 'bg-info' : 'bg-primary') }}" 
                                        style="top: 10px; left: 10px;">
                                        {{ $item->category->cat_name }}
                                    </div>

                                    <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                        <h4>{{ $item->name }}</h4>
                                        <p class="text-limited">{{ $item->description }}</p>
                                        <div class="d-flex justify-content-between flex-lg-wrap">
                                            <p class="text-dark fs-5 fw-bold mb-0">
                                                {{ 'Rp ' . number_format($item->price, 0, ',', '.') }}
                                            </p>
                                            <a href="#" onclick="addToCart({{ $item->id }})" 
                                               class="btn border border-secondary rounded-pill px-3 text-primary">
                                                <i class="fa fa-shopping-bag me-2 text-primary"></i> 
                                                Tambah Keranjang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @section('script')
                            <script>
                                function addToCart(menuId){
                                    $.ajax({
                                        url: '{{ route("cart.add") }}',
                                        type: 'POST',
                                        data: {
                                            id: menuId,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response){
                                            if(response.status == 'success'){
                                                alert(response.message);
                                            }
                                        }
                                        catch(error){
                                            console.log('Error: ' + error);
                                        }
                                    });
                                }
                            </script>
                            @endsection
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection