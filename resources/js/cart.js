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
                    .then(response => {
                        if(!response.ok){
                            return response.json().then(errorData => {
                                alert(errorData.error) 
                            });
                        }
                        return response.json();
                    })
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

        function updateCartTotals(data) {
            if (data.subtotal && document.querySelector('#cart-subtotal')) {
                document.querySelector('#cart-subtotal').textContent = data.subtotal;
            }

            if (data.discount_amount && document.querySelector('#discount-amount')) {
                document.querySelector('#discount-amount').textContent = data.discount_amount;
            }

            if (data.porudzbina_ukupno && document.querySelector('#cart-total')) {
                document.querySelector('#cart-total').textContent = data.porudzbina_ukupno;
            }

            const discountSummary = document.querySelector('#discount-summary');
            const discountCode = document.querySelector('#discount-code');

            if (!discountSummary || !discountCode) {
                return;
            }

            if (data.discount && data.discount.discount_code) {
                discountCode.textContent = data.discount.discount_code;
                discountSummary.classList.remove('hidden');
            } else {
                discountCode.textContent = '';
                discountSummary.classList.add('hidden');
            }
        }

        const discountForm = document.querySelector('#discount-form');
        if (discountForm) {
            discountForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const message = document.querySelector('#discount-message');
                const code = document.querySelector('#discount-code-input').value;

                fetch('/cart/discount/apply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ code })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.error || 'Kod za popust nije važeći.');
                        });
                    }

                    return response.json();
                })
                .then(data => {
                    updateCartTotals(data);
                    message.textContent = data.message || '';
                    message.classList.remove('text-red-600');
                    message.classList.add('text-green-700');
                })
                .catch(error => {
                    message.textContent = error.message;
                    message.classList.remove('text-green-700');
                    message.classList.add('text-red-600');
                });
            });
        }

        const removeDiscountButton = document.querySelector('#remove-discount-btn');
        if (removeDiscountButton) {
            removeDiscountButton.addEventListener('click', function () {
                const message = document.querySelector('#discount-message');

                fetch('/cart/discount/remove', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateCartTotals(data);
                    message.textContent = data.message || '';
                    message.classList.remove('text-red-600');
                    message.classList.add('text-green-700');
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // Increment kolicine stavke porudzbine
        document.querySelectorAll('.increment-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const artikalId = this.getAttribute('data-artikal-id'); 

                fetch('/cart/increment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ artikal_id: artikalId }) 
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            alert(errorData.error); 
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    document.querySelector(`#stavka-total-${artikalId}`).textContent = data.stavka_ukupna_cijena;
                    updateCartTotals(data);
                    document.getElementById('cart-count').textContent = data.cart_count;
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // Decrement kolicine stavke porudzbine
        document.querySelectorAll('.decrement-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const artikalId = this.getAttribute('data-artikal-id'); 

                fetch('/cart/decrement', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ artikal_id: artikalId }) 
                })
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector(`#stavka-total-${artikalId}`).textContent = data.stavka_ukupna_cijena;
                        updateCartTotals(data);
                        document.getElementById('cart-count').textContent = data.cart_count;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Brisanje stavke porudzbine iz cart-a
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
        
                const artikalId = this.getAttribute('data-artikal-id');
        
                if (confirm('Da li ste sigurni da želite da uklonite artikal iz korpe?')) {
                    fetch('/cart/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ artikal_id: artikalId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector(`#stavka-row-${artikalId}`).remove();
                        updateCartTotals(data);
                        document.getElementById('cart-count').textContent = data.cart_count;
        
                        if (data.cart_count === 0) {
                            document.querySelector('.cart').classList.add('centered');
                            document.querySelector('.cart').innerHTML = 
                                `<div class='text-center my-[11rem]'>
                                    <h3 class='w-full text-center text-2xl text-naslov font-extrabold'>Vaša korpa je prazna.</h3>
                                    <a href='/' class='text-blue-500 hover:underline text-xl font-bold mt-3 block'>Početna strana</a>
                                </div>`;
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
        

                

        
