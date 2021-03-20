$(document).ready(function(e){

     const graphurl = 'https://markenyot.herokuapp.com/';
	var query = 'query { products {name,id,price,shop_link,in_stock,img_url,catalog{name}} }';

     jQuery.ajax({
     	method: "POST",
     	url: graphurl,
          crossDomain:true,
          contentType: "application/json",
          data: JSON.stringify({"query": query}),
          beforeSend: function(){
			$('.preloader').html('Loading...');          	

          },
          success: function(data) {
          	$('.preloader').html('');
				var dataresult = data.data.products;
				var total = dataresult.length;
				if ( total > 0 ){
					for (var i = 0; i < total; i++) {
					var id = dataresult[i].id;
					var name = dataresult[i].name;
					var price = dataresult[i].price;
					var img = dataresult[i].img_url;
					var link = dataresult[i].shop_link;
					$('.productLoop').append('<div class="w3-col l4 s6 product_container"><img src="https://lh3.googleusercontent.com/p/'+img+'=s200-w150-c-h150" class="product_image" /> <p class="product_name">'+name+'</p> <p class="product_price">Rp. '+price+'</p> <a href="'+link+'" class="buy_button">Whatsapp <i class="mdi mdi-whatsapp"></i> </a> </div>');

					} 
				} else {
					$('.productLoop').html('<p>Tidak ada produk yang dapat ditampilkan.</p>');
				}          

          },
          error: function(data){
			$('.preloader').html('<p>Gagal memuat produk.</p>');          	
               console.log(data);
          }
     });

})
