// assets/js/app.js - comportamentos da aplicação do passageiro
document.addEventListener('DOMContentLoaded', () => {
  const mmss = n => String(Math.floor(n / 60)).padStart(2, '0') + ':' + String(n % 60).padStart(2, '0');

  // mostrar / ocultar palavra-passe
  document.querySelectorAll('[data-alternar-senha]').forEach(b => {
    b.addEventListener('click', () => {
      const c = document.getElementById(b.dataset.alternarSenha);
      c.type = c.type === 'password' ? 'text' : 'password';
    });
  });

  // contagens regressivas (data-contagem = segundos; data-ao-terminar = recarregar | URL)
  document.querySelectorAll('[data-contagem]').forEach(el => {
    let s = parseInt(el.dataset.contagem, 10);
    el.textContent = mmss(Math.max(s, 0));
    const t = setInterval(() => {
      s--;
      if (s <= 0) {
        clearInterval(t);
        el.textContent = '00:00';
        if (el.dataset.aoTerminar === 'recarregar') location.reload();
        else if (el.dataset.aoTerminar) location.href = el.dataset.aoTerminar;
        return;
      }
      el.textContent = mmss(s);
    }, 1000);
  });

  // redirecionamento automático (splash, a processar pagamento)
  const redir = document.querySelector('[data-redirecionar]');
  if (redir) setTimeout(() => { location.href = redir.dataset.redirecionar; }, parseInt(redir.dataset.atraso || '2500', 10));

  // caixas do código de 6 dígitos
  const caixas = [...document.querySelectorAll('.codigo-caixa')];
  caixas.forEach((c, i) => {
    c.addEventListener('input', () => {
      c.value = c.value.replace(/\D/g, '').slice(0, 1);
      if (c.value && caixas[i + 1]) caixas[i + 1].focus();
    });
    c.addEventListener('keydown', e => { if (e.key === 'Backspace' && !c.value && caixas[i - 1]) caixas[i - 1].focus(); });
    c.addEventListener('paste', e => {
      const t = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
      if (!t) return;
      e.preventDefault();
      t.split('').forEach((ch, k) => { if (caixas[k]) caixas[k].value = ch; });
      caixas[Math.min(t.length, 5)].focus();
    });
  });

  // canal de recuperação (email / telefone)
  document.querySelectorAll('.seletor-canal input').forEach(r => {
    r.addEventListener('change', () => {
      document.querySelectorAll('.seletor-canal label').forEach(l => l.classList.toggle('escolhido', l.contains(r) && r.checked));
      const campo = document.getElementById('identificador');
      if (campo) {
        campo.placeholder = r.value === 'telefone' ? '84 123 4567' : 'nome@email.co.mz';
        campo.type = r.value === 'telefone' ? 'tel' : 'email';
      }
    });
  });

  // mapa de lugares
  const mapa = document.getElementById('mapa-lugares');
  if (mapa) {
    const campo = document.getElementById('campo-assento');
    const rotulo = document.getElementById('lugar-rotulo');
    const btn = document.getElementById('btn-continuar');
    let actual = null;
    mapa.querySelectorAll('.lugar:not(.ocupado)').forEach(b => {
      b.addEventListener('click', () => {
        if (actual) { actual.classList.remove('sel'); actual.textContent = actual.dataset.num; }
        b.classList.add('sel'); b.textContent = '✓'; actual = b;
        campo.value = b.dataset.id; rotulo.textContent = b.dataset.num; btn.disabled = false;
      });
    });
  }

  // métodos de pagamento
  const opcoes = document.querySelectorAll('.opcao-pag');
  if (opcoes.length) {
    const campoTel = document.getElementById('campo-telefone');
    const actualizar = () => {
      opcoes.forEach(o => o.classList.toggle('escolhida', o.querySelector('input').checked));
      const m = document.querySelector('.opcao-pag input:checked').value;
      if (campoTel) campoTel.hidden = m === 'cartao';
      validarTelefonePagamento(m);
    };
    // telefone: só dígitos, 9 no máximo; M-Pesa = 84/85, e-Mola = 86/87
    const tel = document.getElementById('telefone');
    const dica = document.getElementById('dica-telefone');
    const regras = {
      mpesa: { pref: ['84', '85'], texto: 'M-Pesa: 9 dígitos, começando por 84 ou 85 (ex.: 841234567).' },
      emola: { pref: ['86', '87'], texto: 'e-Mola: 9 dígitos, começando por 86 ou 87 (ex.: 871234567).' },
    };
    function validarTelefonePagamento(m) {
      if (!tel) return;
      const r = regras[m];
      if (dica && r) dica.textContent = r.texto;
      let msg = '';
      if (r && m !== 'cartao') {
        if (tel.value.length !== 9) msg = r.texto;
        else if (!r.pref.includes(tel.value.slice(0, 2))) msg = r.texto;
      }
      tel.setCustomValidity(msg);
    }
    if (tel) tel.addEventListener('input', () => {
      tel.value = tel.value.replace(/\D/g, '').slice(0, 9);
      validarTelefonePagamento(document.querySelector('.opcao-pag input:checked').value);
    });
    opcoes.forEach(o => o.querySelector('input').addEventListener('change', actualizar));
    actualizar();
  }

  // notificações de viagem (perfil)
  const notif = document.getElementById('sw-notificacoes');
  if (notif) {
    notif.addEventListener('change', async () => {
      try {
        const r = await fetch(notif.dataset.url, {
          method: 'POST',
          body: new URLSearchParams({ csrf: notif.dataset.csrf, activo: notif.checked ? 1 : 0 }),
        });
        if (!(await r.json()).ok) throw new Error();
      } catch (e) { notif.checked = !notif.checked; alert('Não foi possível guardar a preferência.'); }
    });
  }

  // confirmações
  document.querySelectorAll('form[data-confirmar]').forEach(f =>
    f.addEventListener('submit', e => { if (!confirm(f.dataset.confirmar)) e.preventDefault(); }));

  // bilhete: código QR, guardar e partilhar
  const qr = document.getElementById('qr');
  if (qr) {
    if (window.QRCode) {
      new QRCode(qr, { text: qr.dataset.codigo, width: 200, height: 200, correctLevel: QRCode.CorrectLevel.M });
    } else {
      qr.innerHTML = '<div class="mono" style="padding:30px 10px;font-size:12px;word-break:break-all;max-width:200px">' + qr.dataset.codigo + '</div>';
    }
  }
  const guardar = document.getElementById('btn-guardar');
  if (guardar) guardar.addEventListener('click', () => window.print());
  const partilhar = document.getElementById('btn-partilhar');
  if (partilhar) {
    partilhar.addEventListener('click', async () => {
      const dados = { title: 'Bilhete MozTransport', text: partilhar.dataset.texto };
      if (navigator.share) { try { await navigator.share(dados); } catch (e) {} }
      else if (navigator.clipboard) { await navigator.clipboard.writeText(dados.text); alert('Detalhes do bilhete copiados.'); }
    });
  }
});
