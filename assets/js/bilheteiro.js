// assets/js/bilheteiro.js - venda presencial, lugares ao vivo e emissão
document.addEventListener('DOMContentLoaded', () => {
  const mmss = n => String(Math.floor(n / 60)).padStart(2, '0') + ':' + String(n % 60).padStart(2, '0');
  const mt = n => new Intl.NumberFormat('pt-PT').format(n).replace(/\s|\u00a0/g, '.') + ' MT';

  // contagem regressiva do bloqueio dos lugares
  document.querySelectorAll('[data-contagem]').forEach(el => {
    let s = parseInt(el.dataset.contagem, 10);
    el.textContent = mmss(Math.max(s, 0));
    const t = setInterval(() => {
      s--;
      if (s <= 0) { clearInterval(t); el.textContent = '00:00'; if (el.dataset.aoTerminar === 'recarregar') location.reload(); return; }
      el.textContent = mmss(s);
    }, 1000);
  });

  // passo 2: escolher até 4 lugares
  const form = document.getElementById('form-lugares');
  if (form) {
    const preco = parseFloat(form.dataset.preco);
    const lista = document.getElementById('lista-lugares');
    const campos = document.getElementById('campos-lugares');
    const total = document.getElementById('valor-total');
    const rotulo = document.getElementById('rotulo-total');
    const chip = document.querySelector('#chip-selecao span');
    const botao = document.getElementById('btn-passo3');
    const escolhidos = new Map();
    const nomes = { 0: 'Escolha até 4 lugares', 1: 'A seleccionar lugar para bilhete simples', 2: 'A seleccionar lugares para bilhete duplo' };
    const actualizar = () => {
      const n = escolhidos.size;
      lista.textContent = n ? [...escolhidos.values()].join(', ') : '—';
      total.textContent = mt(preco * n);
      rotulo.textContent = 'Total (' + n + (n === 1 ? ' bilhete)' : ' bilhetes)');
      chip.textContent = nomes[n] || ('A seleccionar ' + n + ' bilhetes');
      campos.innerHTML = '';
      escolhidos.forEach((num, id) => {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = 'lugares[]'; i.value = id; campos.appendChild(i);
      });
      botao.disabled = n === 0;
    };
    document.querySelectorAll('#mapa-bus .bus-lugar:not(.fixo)').forEach(b => {
      b.addEventListener('click', () => {
        const id = b.dataset.id;
        if (escolhidos.has(id)) { escolhidos.delete(id); b.classList.remove('sel'); }
        else if (escolhidos.size < 4) { escolhidos.set(id, b.dataset.num); b.classList.add('sel'); }
        else { alert('Pode vender no máximo 4 lugares de cada vez.'); }
        actualizar();
      });
    });
  }

  // passo 3: resumo em directo e método de pagamento
  const venda = document.getElementById('form-venda');
  if (venda) {
    const nome = document.getElementById('nome');
    const resNome = document.getElementById('res-nome');
    const resMetodo = document.getElementById('res-metodo');
    const metodos = venda.querySelectorAll('.metodo');
    const actualizar = () => {
      resNome.textContent = nome.value.trim() || '—';
      metodos.forEach(m => m.classList.toggle('escolhido', m.querySelector('input').checked));
      const marcado = venda.querySelector('.metodo input:checked');
      resMetodo.textContent = marcado ? marcado.parentElement.textContent.trim() : '—';
    };
    // telefone: só dígitos (9); M-Pesa = 84/85, e-Mola = 86/87, restantes = 82 a 87
    const tel = document.getElementById('telefone');
    const dica = document.getElementById('dica-telefone');
    const regras = {
      mpesa: { pref: ['84', '85'], texto: 'M-Pesa: 9 dígitos, começando por 84 ou 85.' },
      emola: { pref: ['86', '87'], texto: 'e-Mola: 9 dígitos, começando por 86 ou 87.' },
    };
    const validarTel = () => {
      if (!tel) return;
      const m = venda.querySelector('.metodo input:checked');
      const r = regras[m ? m.value : ''];
      const pref = r ? r.pref : ['82', '83', '84', '85', '86', '87'];
      const texto = r ? r.texto : 'Digite os 9 dígitos (82, 83, 84, 85, 86 ou 87 + 7 dígitos).';
      if (dica) dica.textContent = texto;
      tel.setCustomValidity(tel.value.length === 9 && pref.includes(tel.value.slice(0, 2)) ? '' : texto);
    };
    if (tel) tel.addEventListener('input', () => { tel.value = tel.value.replace(/\D/g, '').slice(0, 9); validarTel(); });
    metodos.forEach(m => m.querySelector('input').addEventListener('change', validarTel));
    validarTel();
    nome.addEventListener('input', actualizar);
    metodos.forEach(m => m.querySelector('input').addEventListener('change', actualizar));
    actualizar();
  }

  // emitir: QR codes e impressão
  document.querySelectorAll('.qr-item').forEach(el => {
    if (window.QRCode) new QRCode(el, { text: el.dataset.codigo, width: 150, height: 150, correctLevel: QRCode.CorrectLevel.M });
    else el.innerHTML = '<div style="width:150px;font:11px monospace;word-break:break-all">' + el.dataset.codigo + '</div>';
  });
  document.querySelectorAll('[data-imprimir]').forEach(b => b.addEventListener('click', () => window.print()));

  // lugares ao vivo: actualiza a cada 8 segundos
  const vivo = document.getElementById('mapa-vivo');
  if (vivo) {
    const actualizar = async () => {
      try {
        const r = await fetch(vivo.dataset.url, { cache: 'no-store' });
        const d = await r.json();
        if (!d.ok) return;
        const oc = new Set(d.ocupados.map(String));
        vivo.querySelectorAll('.bus-lugar').forEach(l => l.classList.toggle('ocupado', oc.has(l.dataset.id)));
        document.getElementById('n-ocupados').textContent = d.n_ocupados + ' ocupados';
        document.getElementById('n-livres').textContent = d.n_livres + ' livres';
      } catch (e) { /* ignora falhas pontuais de rede */ }
    };
    setInterval(actualizar, 8000);
  }
});
