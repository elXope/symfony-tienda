//Immediately-Invoked Function Expression (IIFE)
//INFO PRODUCTO
(function(){
    const infoProduct = $("#infoProduct");
    $( "a.open-info-product" ).click(function(event) {
      event.preventDefault();
      const id = $( this ).attr('data-id');
      const href = `/api/show/${id}`;
      $.get( href, function(data) {
        $( infoProduct ).find( "#productName" ).text(data.name);
        $( infoProduct ).find( "#productPrice" ).text(data.price);
        $( infoProduct ).find( "#productImage" ).attr("src", "/img/" + data.photo);
        infoProduct.modal('show');
      })
    });
    $(".closeInfoProduct").click(function (e) {
      infoProduct.modal('hide');
    });
})();

//Immediately-Invoked Function Expression (IIFE)
//CARRO
(function(){
    const cartModal = $("#cart-modal");
    $( "a.open-cart-product" ).click(function(event) {
      event.preventDefault();
      const id = $( this ).attr('data-id');
      const href = `/cart/add/${id}`;
      $.get( href, function(data) {
        $( cartModal ).find( "#productName" ).text(data.name);
        $( cartModal ).find( "#productQuantity" ).text(data.quantity);
        $( cartModal ).find( "#productImage" ).attr("src", "/img/" + data.photo);
        cartModal.modal('show');
      });
      $('#update_cart').submit(function(evento) {
        evento.preventDefault();
        let productQuantity = $('#productQuantity').val();
        const href2 = `/cart/update/${id}/${productQuantity}`;
        $.post(href2, function() {
          cartModal.modal('hide');
          $("#nCarrito").text(nItems.totalItems);
        });
      });
    });
    $(".closeCart").click(function (e) {
      cartModal.modal('hide');
    });
})();

// Delete element del carro
(function() {
  $('.remove').click(function(evento) {
    evento.preventDefault();
    const id = $(this).attr('data-id');
    const href = `/cart/delete/${id}`;
    let price = $(`#item-${id}-price`).text();
    price = parseFloat(price.substring(1));
    let totalPrice = parseFloat($('#totalCart').text().substring(7)) - price;
    $.post(href, function() {
      $(`#item-${id}`).hide('slow', function(){ $(`#item-${id}`).remove(); });
      $('#totalCart').text('Total $' + totalPrice);
    });
  });
})();