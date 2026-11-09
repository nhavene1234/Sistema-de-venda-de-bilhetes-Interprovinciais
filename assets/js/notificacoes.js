// assets/js/notificacoes.js - sino de notificações (funciona em todos os perfis)
(function () {
  const btn = document.querySelector('[data-notif]');
  if (!btn) return;
  const url = btn.dataset.url, csrf = btn.dataset.csrf;
  const badge = btn.querySelector('.notif-badge');
  let painel = null, aberto = false, busy = false;

  const svg = (d) => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + d + '</svg>';
  const ICONES = {
    bilhete:  svg('<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 17v2M13 11v2"/>'),
    alerta:   svg('<path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/>'),
    relogio:  svg('<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'),
    bus:      svg('<rect x="5" y="3" width="14" height="15" rx="2.5"/><path d="M5 11h14M8 21v-3M16 21v-3"/><circle cx="9" cy="14.5" r=".7"/><circle cx="15" cy="14.5" r=".7"/>'),
    user:     svg('<circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/>'),
    escudo:   svg('<path d="M12 3 4 6v6c0 4.5 3.3 8 8 9 4.7-1 8-4.5 8-9V6l-8-3Z"/><path d="m9 12 2 2 4-4"/>'),
    rota:     svg('<circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M8 19h8a3 3 0 0 0 0-6H8a3 3 0 0 1 0-6h8"/>'),
    dinheiro: svg('<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/>'),
    edificio: svg('<rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M9 7h.01M13 7h.01M9 11h.01M13 11h.01M10 21v-4h4v4"/>'),
    documento:svg('<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8Z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>'),
    suporte:  svg('<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3.5"/><path d="m5.6 5.6 3.9 3.9M14.5 14.5l3.9 3.9M18.4 5.6l-3.9 3.9M9.5 14.5l-3.9 3.9"/>'),
    sino:     '<svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>',
  };

  function setBadge(n) {
    if (n > 0) {
      badge.textContent = n > 9 ? '9+' : String(n);
      badge.hidden = false;
      btn.setAttribute('aria-label', 'Notificações (' + n + (n === 1 ? ' nova)' : ' novas)'));
    } else {
      badge.hidden = true;
      btn.setAttribute('aria-label', 'Notificações');
    }
  }

  async function pedir(extra) {
    const r = await fetch(url + (extra || ''), { credentials: 'same-origin', headers: { Accept: 'application/json' } });
    if (!r.ok) throw new Error(String(r.status));
    return r.json();
  }

  async function contar() {
    if (aberto) return;
    try { const d = await pedir('?contar=1'); if (d.ok) setBadge(d.novas); } catch (e) { /* silencioso */ }
  }

  function el(tag, cls, texto) {
    const x = document.createElement(tag);
    if (cls) x.className = cls;
    if (texto != null) x.textContent = texto;
    return x;
  }

  function vazio(titulo, texto) {
    const d = el('div', 'notif-vazio');
    d.innerHTML = ICONES.sino;
    d.appendChild(el('b', null, titulo));
    d.appendChild(el('span', null, texto));
    return d;
  }

  function item(n) {
    const a = el('a', 'notif-item' + (n.nova ? ' nova' : ''));
    a.href = n.link;
    const ico = el('span', 'notif-ico ' + (n.tipo || 'info'));
    ico.innerHTML = ICONES[n.icone] || ICONES.sino;
    const corpo = el('div', 'notif-corpo');
    corpo.appendChild(el('b', null, n.titulo));
    corpo.appendChild(el('p', null, n.texto));
    corpo.appendChild(el('small', null, n.quando));
    a.appendChild(ico); a.appendChild(corpo);
    return a;
  }

  function desenhar(d) {
    const lista = painel.querySelector('.notif-lista');
    const pill = painel.querySelector('.notif-cab span');
    lista.textContent = '';
    pill.hidden = !(d.novas > 0);
    pill.textContent = d.novas + (d.novas === 1 ? ' nova' : ' novas');
    if (d.desactivadas) {
      lista.appendChild(vazio('Notificações desactivadas', 'Pode voltar a activá-las no seu perfil.'));
      return;
    }
    if (!d.itens.length) {
      lista.appendChild(vazio('Sem notificações novas'));
      return;
    }
    d.itens.forEach(n => lista.appendChild(item(n)));   // só vêm as novas; ao abrir passam a vistas e desaparecem
  }

  function posicionar() {
    const r = btn.getBoundingClientRect();
    const w = Math.min(380, window.innerWidth - 16);
    const left = Math.max(8, Math.min(r.right - w, window.innerWidth - w - 8));
    const top = r.bottom + 8;
    painel.style.width = w + 'px';
    painel.style.left = left + 'px';
    painel.style.top = top + 'px';
    painel.style.maxHeight = Math.max(220, window.innerHeight - top - 12) + 'px';
  }

  function criar() {
    painel = el('div', 'notif-painel');
    painel.hidden = true;
    painel.setAttribute('role', 'dialog');
    painel.setAttribute('aria-label', 'Notificações');
    const cab = el('div', 'notif-cab');
    cab.appendChild(el('h3', null, 'Notificações'));
    const pill = el('span', null, ''); pill.hidden = true;
    cab.appendChild(pill);
    painel.appendChild(cab);
    painel.appendChild(el('div', 'notif-lista'));
    document.body.appendChild(painel);
  }

  async function abrir() {
    if (!painel) criar();
    aberto = true;
    btn.setAttribute('aria-expanded', 'true');
    painel.hidden = false;
    posicionar();
    const lista = painel.querySelector('.notif-lista');
    lista.textContent = '';
    lista.appendChild(vazio('A carregar…', ''));
    try {
      const d = await pedir();
      if (!d.ok) throw new Error();
      if (!aberto) return;
      desenhar(d);
      posicionar();
      if (d.novas > 0 && !busy) {   // ao abrir, as novas passam a "vistas" (o destaque mantém-se até fechar)
        busy = true;
        fetch(url, { method: 'POST', credentials: 'same-origin', body: new URLSearchParams({ acao: 'vistas', csrf, ate: d.agora }) })
          .then(() => setBadge(0)).catch(() => {}).finally(() => { busy = false; });
      } else if (d.novas === 0) { setBadge(0); }
    } catch (e) {
      lista.textContent = '';
      lista.appendChild(vazio('Não foi possível carregar', 'Verifique a ligação e tente outra vez.'));
    }
  }

  function fechar() {
    if (!painel) return;
    aberto = false;
    painel.hidden = true;
    btn.setAttribute('aria-expanded', 'false');
  }

  btn.addEventListener('click', (ev) => { ev.preventDefault(); ev.stopPropagation(); aberto ? fechar() : abrir(); });
  document.addEventListener('click', (ev) => { if (aberto && !painel.contains(ev.target) && !btn.contains(ev.target)) fechar(); });
  document.addEventListener('keydown', (ev) => { if (ev.key === 'Escape' && aberto) { fechar(); btn.focus(); } });
  window.addEventListener('resize', () => { if (aberto) posicionar(); });
  window.addEventListener('scroll', () => { if (aberto) posicionar(); }, { passive: true });

  contar();
  setInterval(contar, 60000);
  document.addEventListener('visibilitychange', () => { if (!document.hidden) contar(); });
})();
