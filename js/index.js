$(function () {
  $('#preco_minimo').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
  $('#preco_maximo').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

  const baseUrlUploads = 'http://localhost/site_imoveis/painel/uploads/';

  // Sistema de busca pelo nome do imóvel
  $('#texto-busca').keyup(function() {
    let busca = $('#texto-busca').val();

    $.ajax({
      url: 'http://localhost/site_imoveis/ajax/buscar-imovel.php',
      method: 'GET',
      data: {
        busca: busca,
      },
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',
    }).done(function(data) {
      $('.lista-imoveis').empty();
      for (let i = 0; i < data.length; i++) {
        $('.lista-imoveis').prepend(`
          <div class="single-imovel">
            <img src="${baseUrlUploads}${data[i][0]}" alt="">
            <div>
              <p>Nome do Imóvel: ${data[i].nome}</p>
              <p>Área do Imóvel: ${data[i].area}</p>
              <p>Preço do Imóvel: R$ ${data[i].preco}</p>
            </div>
          </div>  
        `)
      }
      $('.lista-imoveis').prepend(`
        <h2>Listando ${data.length} imóveis</h2>
      `)
    })
  })

})