        const addToCartButtons = document.querySelectorAll('.add-to-cart');

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // Trazenje identifikatora svih knjiga
                const artikalId = this.getAttribute('data-artikal-id');

                fetch('/add-to-cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            artikal_id: artikalId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                            // Azuriranje broja artikala u korpi u navbar-u
                            document.getElementById('cart-count').textContent = data.cart_count;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        function updateCartCount() {
            fetch('/cart-count')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.cart_count;
                })
                .catch(error => {
                    console.error('Error fetching cart count:', error);
                });
         }
    
        // EventListener za navigaciju na prethodnu stranicu ili ucitavanje stranice
        window.addEventListener('pageshow', updateCartCount);

        // Increment kolicine stavke artikla
        // Increment quantity for cart item
        document.querySelectorAll('.increment-btn').forEach(button => {
            button.addEventListener('click', function () {
                const artikalId = this.getAttribute('data-artikal-id'); // Use artikal_id

                fetch('/cart/increment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ artikal_id: artikalId }) // Send artikal_id in the request
                })
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector(`#stavka-total-${artikalId}`).textContent = data.stavka_ukupna_cijena;
                        document.querySelector('#cart-total').textContent = data.porudzbina_ukupno;
                        document.getElementById('cart-count').textContent = data.cart_count;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Decrement quantity for cart item
        document.querySelectorAll('.decrement-btn').forEach(button => {
            button.addEventListener('click', function () {
                const artikalId = this.getAttribute('data-artikal-id'); // Use artikal_id

                fetch('/cart/decrement', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ artikal_id: artikalId }) // Send artikal_id in the request
                })
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector(`#stavka-total-${artikalId}`).textContent = data.stavka_ukupna_cijena;
                        document.querySelector('#cart-total').textContent = data.porudzbina_ukupno;
                        document.getElementById('cart-count').textContent = data.cart_count;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

                

        