// assets/js/gestor.js - modais de edição de rotas e impressão
document.addEventListener('DOMContentLoaded', () => {
  // editar rota: preenche o modal com os dados da linha
  document.querySelectorAll('[data-editar-rota]').forEach(b => {
    b.addEventListener('click', () => {
      const m = document.getElementById('modal-editar-rota');
      m.querySelector('[name=id]').value = b.dataset.id;
      m.querySelector('[name=origem]').value = b.dataset.origem;
      m.querySelector('[name=destino]').value = b.dataset.destino;
      m.querySelector('[name=distancia]').value = b.dataset.distancia;
      m.querySelector('[name=duracao]').value = b.dataset.duracao;
      m.querySelector('[name=preco]').value = b.dataset.preco;
      m.querySelector('[data-rota-nome]').textContent = b.dataset.origem + ' → ' + b.dataset.destino;
      m.showModal();
    });
  });

  // programar viagens numa rota existente
  document.querySelectorAll('[data-programar-rota]').forEach(b => {
    b.addEventListener('click', () => {
      const m = document.getElementById('modal-programar');
      m.querySelector('[name=id]').value = b.dataset.id;
      m.querySelector('[data-rota-nome]').textContent = b.dataset.origem + ' → ' + b.dataset.destino;
      m.showModal();
    });
  });

  // imprimir / exportar PDF (o navegador permite «Guardar como PDF»)
  document.querySelectorAll('[data-imprimir-pagina]').forEach(b => b.addEventListener('click', () => window.print()));
});

// ---------- reservas: seleccionar, exportar e ver detalhes ----------
document.addEventListener('DOMContentLoaded', () => {
  const tabela = document.getElementById('tabela-reservas');
  if (tabela) {
    const todos = tabela.querySelector('[data-todos]');
    const linhas = [...tabela.querySelectorAll('[data-sel]')];
    const exp = document.getElementById('btn-exportar');
    const base = exp.getAttribute('href');
    const actualizar = () => {
      const ids = linhas.filter(l => l.checked).map(l => l.value);
      exp.href = ids.length ? base + '&ids=' + ids.join(',') : base;
      exp.textContent = ids.length ? '⬇ Exportar CSV (' + ids.length + ')' : '⬇ Exportar CSV';
    };
    todos.addEventListener('change', () => { linhas.forEach(l => { l.checked = todos.checked; }); actualizar(); });
    linhas.forEach(l => l.addEventListener('change', actualizar));
    document.querySelectorAll('[data-detalhes]').forEach(b => b.addEventListener('click', () => {
      const m = document.getElementById('modal-reserva');
      const dl = m.querySelector('dl');
      dl.innerHTML = '';
      JSON.parse(b.dataset.detalhes).forEach(([k, v]) => {
        const dt = document.createElement('dt'); dt.textContent = k;
        const dd = document.createElement('dd'); dd.textContent = v;
        dl.append(dt, dd);
      });
      m.showModal();
    }));
  }

  const mes = ['jan.', 'fev.', 'mar.', 'abr.', 'mai.', 'jun.', 'jul.', 'ago.', 'set.', 'out.', 'nov.', 'dez.'];
  const estados = { disponivel: 'Disponível', manutencao: 'Em manutenção', folga: 'De folga', inactivo: 'Inactivo' };

  // ---------- formulário do autocarro: resumo e prévia dos lugares ----------
  const fb = document.getElementById('form-autocarro');
  if (fb) {
    const q = s => fb.querySelector(s);
    const el = id => document.getElementById(id);
    const letras = { '2+2': ['A', 'B', 'C', 'D'], '2+1': ['A', 'B', 'C'] };
    const actualizar = () => {
      el('rs-codigo').textContent = q('[name=codigo_frota]').value.trim() || '—';
      el('rs-veiculo').textContent = q('[name=marca]').value + ' · ' + (q('[name=modelo]').value.trim() || '—');
      el('rs-matricula').textContent = q('[name=matricula]').value.trim().toUpperCase() || '—';
      el('rs-ano').textContent = q('[name=ano_fabrico]').value;
      el('rs-servico').textContent = q('[name=tipo_servico]').value;
      el('rs-estado').textContent = estados[q('[name=estado]').value];
      const n = Math.max(0, Math.min(60, parseInt(q('[name=assentos]').value, 10) || 0));
      const cfg = q('[name=configuracao]').value;
      const L = letras[cfg];
      el('rs-lugares').textContent = n;
      el('rs-config').textContent = cfg.replace('+', ' + ');
      const filas = Math.ceil(n / L.length);
      let html = '', c = 0;
      for (let f = 1; f <= filas; f++) {
        html += '<div class="pv-fila">';
        L.forEach((l, i) => {
          html += c < n ? '<span>' + f + l + '</span>' : '<span class="vazio"></span>';
          c++;
          if (i === 1) html += '<i></i>';
        });
        html += '</div>';
      }
      el('rs-previa').innerHTML = html;
      el('rs-previa-nota').textContent = 'Prévia · ' + filas + ' filas de ' + L.length + ' assentos';
    };
    fb.addEventListener('input', actualizar);
    fb.addEventListener('change', actualizar);
    actualizar();
  }

  // ---------- formulário do motorista: resumo e validade da carta ----------
  const fm = document.getElementById('form-motorista');
  if (fm) {
    const q = s => fm.querySelector(s);
    const el = id => document.getElementById(id);
    const actualizar = () => {
      const nome = q('[name=nome]').value.trim();
      const p = nome.split(/\s+/).filter(Boolean);
      el('rs-iniciais').textContent = p.length ? (p[0][0] + (p.length > 1 ? p[p.length - 1][0] : '')).toUpperCase() : '—';
      el('rs-nome').textContent = nome || 'Nome do motorista';
      el('rs-codigo').textContent = (q('[name=codigo]').value.trim() || '—') + ' · ' + fm.dataset.transportadora;
      el('rs-estado').textContent = estados[q('[name=estado]').value];
      el('rs-telefone').textContent = q('[name=telefone]').value.trim() || '—';
      const morada = q('[name=morada]').value.trim();
      el('rs-localidade').textContent = morada ? morada.split(',').pop().trim() : '—';
      el('rs-carta').textContent = q('[name=carta_numero]').value.trim() || '—';
      const v = q('[name=carta_validade]').value;
      const badge = el('rs-validade-badge');
      const aviso = el('aviso-doc');
      if (v) {
        const [a, m, d] = v.split('-');
        el('rs-validade').textContent = parseInt(d, 10) + ' ' + mes[parseInt(m, 10) - 1] + ' ' + a;
        const valida = v >= new Date().toISOString().slice(0, 10);
        badge.textContent = valida ? 'Carta válida' : 'Carta expirada';
        badge.className = 'etq-g ' + (valida ? 'etq-pago' : 'etq-cancelado');
        aviso.classList.toggle('invalido', !valida);
        aviso.querySelector('b').textContent = valida ? 'Documentação válida' : 'Carta de condução expirada';
      } else {
        el('rs-validade').textContent = '—';
        badge.textContent = ''; badge.className = '';
      }
    };
    fm.addEventListener('input', actualizar);
    fm.addEventListener('change', actualizar);
    actualizar();
  }
});

// ---------- cobradores: seleccionar viagens a atribuir ----------
document.addEventListener('DOMContentLoaded', () => {
  const todos = document.querySelector('[data-todos-viagens]');
  if (!todos) return;
  const caixas = [...document.querySelectorAll('[data-viagem-sel]')];
  const contar = () => {
    const n = caixas.filter(c => c.checked).length;
    document.querySelectorAll('[data-n-sel]').forEach(e => { e.textContent = n; });
    document.querySelectorAll('[data-precisa-sel]').forEach(b => { b.disabled = n === 0; });
    todos.checked = caixas.length > 0 && n === caixas.length;
  };
  todos.addEventListener('change', () => { caixas.forEach(c => { c.checked = todos.checked; }); contar(); });
  caixas.forEach(c => c.addEventListener('change', contar));
  contar();
});
