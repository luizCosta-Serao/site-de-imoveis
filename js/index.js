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

  // Sistema de busca pela área do imóvel
  $('#area_minima, #area_maxima').keyup(function() {
    let areaMinima = $('#area_minima').val();
    let areaMaxima = $('#area_maxima').val();
    console.log(areaMaxima)
    $.ajax({
      url: 'http://localhost/site_imoveis/ajax/buscar-area-imovel.php',
      method: 'GET',
      data: {
        areaMinima: areaMinima,
        areaMaxima: areaMaxima,
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

  // Sistema de busca pelo preço do imóvel
  $('#preco_minimo, #preco_maximo').keyup(function() {
    let precoMinimo = $('#preco_minimo').val();
    let precoMaximo = $('#preco_maximo').val();

    $.ajax({
      url: 'http://localhost/site_imoveis/ajax/buscar-preco-imovel.php',
      method: 'GET',
      data: {
        precoMinimo: precoMinimo,
        precoMaximo: precoMaximo,
      },
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',
    }).done(function(data) {
      $('.lista-imoveis').empty();
      if (typeof data === 'string') {
        $('.lista-imoveis').prepend(`
          <p>Não foi encontrado nenhum resultado</p>
        `);
      } else {
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
      }
    })
  })
})