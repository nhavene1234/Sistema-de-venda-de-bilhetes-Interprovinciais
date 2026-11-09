<?php
require_once __DIR__ . '/_base.php';
cb_abrir('Validar bilhete', 'scan-app');
?>
<form id="f-csrf" hidden><?= csrf_campo() ?></form>
<video id="video" playsinline muted></video>

<div class="scan-topo">
  <a class="scan-btn" href="<?= e(url('cobrador/index.php')) ?>" aria-label="Fechar"><?= cb_ic('x') ?></a>
  <div class="tit"><b>Validar bilhete</b><small>Scanner activo</small></div>
  <button class="scan-btn" id="lanterna" type="button" aria-label="Lanterna"><?= cb_ic('torch', 20) ?></button>
</div>
<div class="scan-centro">
  <div class="moldura"><i></i><i></i><i></i><i></i></div>
  <div class="scan-texto"><b id="dica">Aponte para o código QR do bilhete</b><span>A validação é automática</span>
    <button type="button" class="btn-camara" id="tentar-camara" hidden>Tentar abrir a câmara</button>
    <small class="diag" id="diag"></small></div>
</div>
<button class="btn-foto" id="abrir-foto" type="button">Ler QR de uma foto</button>
<input type="file" id="ficheiro-qr" accept="image/*" hidden>
<button class="btn-manual" id="abrir-manual" type="button"><?= cb_ic('kbd', 20) ?> Inserir código manualmente</button>

<div class="folha" id="folha">
  <h3>Inserir código do bilhete</h3>
  <input id="codigo" type="text" placeholder="Ex.: MT-2026-000015" inputmode="text" autocomplete="off" autocapitalize="characters">
  <div class="acoes"><button class="s" type="button" id="cancelar">Cancelar</button><button class="p" type="button" id="verificar">Validar</button></div>
</div>

<div class="res" id="res"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.min.js"></script>
<script>if (!window.jsQR) document.write('<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"><\/script>');</script>
<script>
(function () {
  var API = <?= json_encode(url('cobrador/api.php')) ?>;
  var $ = function (id) { return document.getElementById(id); };
  var video = $('video'), res = $('res'), folha = $('folha'), dica = $('dica');
  var cv = document.createElement('canvas'), cx = cv.getContext('2d', { willReadFrequently: true });
  var parado = false, track = null, luz = false;
  var ICO_OK = '<svg width="76" height="76" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5l5 5L20 7"/></svg>';
  var ICO_X  = '<svg width="76" height="76" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.2" stroke-linecap="round"><path d="M5 5l14 14M19 5L5 19"/></svg>';
  function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }

  function post(dados) {
    var f = new FormData($('f-csrf'));
    for (var k in dados) f.append(k, dados[k]);
    return fetch(API, { method: 'POST', body: f, credentials: 'same-origin' }).then(function (r) { return r.json(); });
  }

  function fechar() { res.className = 'res'; res.innerHTML = ''; parado = false; }

  function mostrar(r) {
    parado = true;
    if (navigator.vibrate) navigator.vibrate(r.ok ? 60 : [80, 60, 80]);
    if (r.ok) {
      res.className = 'res valido';
      res.innerHTML = '<div class="circ">' + ICO_OK + '</div><h1>VÁLIDO</h1><h2>' + esc(r.passageiro) + '</h2>' +
        '<div class="assento"><small>ASSENTO</small><b>' + esc(r.assento) + '</b></div><p>' + esc(r.rota) + ' · ' + esc(r.hora) + '</p>' +
        '<div class="rodape"><button class="p" id="b-conf">Confirmar embarque</button><button class="l" id="b-outro">Ler outro bilhete</button></div>';
      $('b-conf').onclick = function () {
        this.disabled = true;
        post({ acao: 'confirmar', codigo: r.codigo }).then(function (x) {
          if (x.ok) fechar(); else mostrar(x);
        }).catch(function () { alert('Sem ligação. Tente novamente.'); });
      };
    } else {
      res.className = 'res invalido';
      res.innerHTML = '<div class="circ">' + ICO_X + '</div><h1>INVÁLIDO</h1><h2>' + esc(r.titulo) + '</h2><p>' + esc(r.msg) + '</p>' +
        (r.codigo ? '<span class="cod">' + esc(r.codigo) + '</span>' : '') +
        '<div class="rodape"><button class="p" id="b-rec">Recusar embarque</button><button class="l" id="b-outro">Ler outro bilhete</button></div>';
      $('b-rec').onclick = function () {
        post({ acao: 'recusar', codigo: r.codigo || '', motivo: r.titulo || '' }).finally(fechar);
      };
    }
    $('b-outro').onclick = fechar;
  }

  function verificar(codigo) {
    parado = true;
    post({ acao: 'verificar', codigo: codigo }).then(mostrar)
      .catch(function () { parado = false; dica.textContent = 'Sem ligação. Tente novamente.'; });
  }

  /* Câmara + leitura de QR */
  var detector = null, ocupado = false, aCorrer = false, ultimo = 0, quadros = 0, diag = $('diag');
  try { if ('BarcodeDetector' in window) detector = new BarcodeDetector({ formats: ['qr_code'] }); } catch (e) { detector = null; }

  /* Tenta ler um QR numa região do vídeo/imagem (sx,sy,sw,sh) redimensionada para w x h */
  function decodificar(fonte, sx, sy, sw, sh, w, h, inv) {
    cv.width = w; cv.height = h;
    cx.drawImage(fonte, sx, sy, sw, sh, 0, 0, w, h);
    var q = jsQR(cx.getImageData(0, 0, w, h).data, w, h, { inversionAttempts: inv });
    return q && q.data ? q.data : null;
  }

  /* 1) recorte central (onde está a moldura) em alta resolução; 2) imagem inteira; 3) com inversão de cores */
  function lerFonte(fonte, vw, vh, tentarInversao) {
    var lado = Math.min(vw, vh), inv = tentarInversao ? 'attemptBoth' : 'dontInvert';
    var r = decodificar(fonte, (vw - lado) / 2, (vh - lado) / 2, lado, lado, Math.min(lado, 720), Math.min(lado, 720), inv);
    if (!r) { var k = Math.min(1, 960 / vw); r = decodificar(fonte, 0, 0, vw, vh, Math.round(vw * k), Math.round(vh * k), inv); }
    return r;
  }

  function ciclo(t) {
    if (!parado && video.readyState >= 2 && video.videoWidth && t - ultimo > 90) {
      ultimo = t; quadros++;
      if (window.jsQR) {
        var r = lerFonte(video, video.videoWidth, video.videoHeight, quadros % 4 === 0);
        if (r) { dica.textContent = 'QR lido. A verificar…'; verificar(r); }
      } else if (detector && !ocupado) {
        ocupado = true;
        detector.detect(video).then(function (c) { if (c.length && !parado) verificar(c[0].rawValue); })
          .catch(function () {}).then(function () { ocupado = false; });
      }
    }
    if (diag && quadros % 6 === 0 && video.videoWidth)
      diag.textContent = 'Câmara ' + video.videoWidth + '×' + video.videoHeight + ' · leitor ' + (window.jsQR ? 'jsQR' : detector ? 'nativo' : 'indisponível');
    requestAnimationFrame(ciclo);
  }

  function erroCamara(e) {
    var n = e && e.name, t;
    if (n === 'NotAllowedError' || n === 'SecurityError') t = 'Câmara bloqueada. Permita o acesso à câmara no navegador (ícone do cadeado) e tente de novo.';
    else if (n === 'NotFoundError' || n === 'OverconstrainedError') t = 'Este dispositivo não tem câmara. Use o código manual.';
    else if (n === 'NotReadableError' || n === 'AbortError') t = 'A câmara está a ser usada por outra aplicação. Feche-a e tente de novo.';
    else t = 'Câmara indisponível. Use o código manual.';
    dica.textContent = t;
    $('tentar-camara').hidden = false;
  }

  function abrirCamara() {
    $('tentar-camara').hidden = true;
    if (!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)) {
      dica.textContent = window.isSecureContext ? 'Este navegador não suporta câmara. Use o código manual.'
        : 'A câmara só funciona em HTTPS (ou localhost). Abra o sistema por https:// ou use o código manual.';
      return;
    }
    if (!window.jsQR && !detector) dica.textContent = 'Leitor de QR não carregou (sem internet?). Use o código manual.';
    var pedir = function (c) { return navigator.mediaDevices.getUserMedia({ video: c, audio: false }); };
    pedir({ facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } })
      .catch(function () { return pedir({ facingMode: { ideal: 'environment' } }); })
      .catch(function () { return pedir(true); }).then(function (s) {
        video.srcObject = s; track = s.getVideoTracks()[0];
        try { track.applyConstraints({ advanced: [{ focusMode: 'continuous' }] }).catch(function () {}); } catch (e) {}
        var p = video.play(); if (p && p.catch) p.catch(function () {});
        if (window.jsQR || detector) dica.textContent = 'Aponte para o código QR do bilhete';
        if (!aCorrer) { aCorrer = true; requestAnimationFrame(ciclo); }
      }).catch(erroCamara);
  }
  /* Ler QR a partir de uma foto/imagem (alternativa se a câmara ao vivo falhar) */
  $('abrir-foto').onclick = function () { $('ficheiro-qr').click(); };
  $('ficheiro-qr').onchange = function () {
    var f = this.files && this.files[0]; this.value = '';
    if (!f) return;
    var img = new Image();
    img.onload = function () {
      URL.revokeObjectURL(img.src);
      var w = img.naturalWidth, h = img.naturalHeight, r = null;
      if (window.jsQR) {
        var escalas = [1, 0.5, 0.25];
        for (var i = 0; i < escalas.length && !r; i++) {
          var ww = Math.max(200, Math.min(1600, Math.round(w * escalas[i]))), hh = Math.round(h * ww / w);
          r = decodificar(img, 0, 0, w, h, ww, hh, 'attemptBoth');
        }
      }
      if (r) verificar(r); else { dica.textContent = 'Não foi possível ler um QR nessa imagem.'; }
    };
    img.onerror = function () { dica.textContent = 'Imagem inválida.'; };
    img.src = URL.createObjectURL(f);
  };
  $('tentar-camara').onclick = abrirCamara;
  abrirCamara();

  $('lanterna').onclick = function () {
    if (!track || !track.applyConstraints) return;
    luz = !luz;
    track.applyConstraints({ advanced: [{ torch: luz }] }).then(function () { $('lanterna').classList.toggle('ligado', luz); }).catch(function () { luz = false; });
  };

  /* Código manual */
  $('abrir-manual').onclick = function () { parado = true; folha.classList.add('aberta'); setTimeout(function () { $('codigo').focus(); }, 260); };
  $('cancelar').onclick = function () { folha.classList.remove('aberta'); $('codigo').blur(); $('codigo').value = ''; parado = false; };
  $('verificar').onclick = function () { var c = $('codigo').value; folha.classList.remove('aberta'); $('codigo').blur(); if (c.trim()) { $('codigo').value = ''; verificar(c); } else parado = false; };
  $('codigo').addEventListener('keydown', function (e) { if (e.key === 'Enter') $('verificar').click(); });
})();
</script>
<?php cb_fechar(); cb_fim(); ?>
