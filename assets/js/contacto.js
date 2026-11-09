// assets/js/contacto.js - validação em directo dos contactos telefónicos (input[data-contacto]).
// Regra: 9 dígitos, a começar por 82, 83, 84, 85, 86 ou 87 (aceita +258 e espaços, mostra só os 9 dígitos).
(function () {
  var PREF = ['82', '83', '84', '85', '86', '87'];
  var MSG = 'Contacto inválido: digite 9 dígitos a começar por 82, 83, 84, 85, 86 ou 87 (ex.: 841234567).';

  function normalizar(v) {
    v = (v || '').trim();
    if (!v || /[^0-9+\s.\-]/.test(v) || v.indexOf('+', 1) > 0) return null;
    var d = v.replace(/\D/g, ''), m = d.match(/^(?:00)?258(\d{9})$/);
    if (m) d = m[1];
    if (!/^\d{9}$/.test(d)) return null;
    return PREF.indexOf(d.slice(0, 2)) >= 0 ? d : null;
  }

  function aviso(el) {
    if (!el._aviso) {
      var s = document.createElement('small');
      s.style.cssText = 'display:block;margin-top:6px;font-size:12px;line-height:1.35;color:#d92d20';
      s.setAttribute('role', 'alert');
      var alvo = el.closest('.campo, .campo-s') || el.parentNode;
      alvo.appendChild(s);
      el._aviso = s;
    }
    return el._aviso;
  }

  function validar(el, mostrar) {
    var v = el.value.trim(), msg = '';
    if (v === '') { if (el.required) msg = 'Indique o contacto telefónico (9 dígitos).'; }
    else if (normalizar(v) === null) msg = MSG;
    el.setCustomValidity(msg);
    if (mostrar || el._tocado) { aviso(el).textContent = msg; el.style.borderColor = msg ? '#d92d20' : ''; }
    return msg === '';
  }

  function arrumar(el) {                      // valor válido -> só os 9 dígitos
    var n = normalizar(el.value);
    if (n) el.value = n;
  }

  function iniciar(el) {
    if (el._contacto) return;
    el._contacto = true;
    el.setAttribute('inputmode', 'numeric');
    el.setAttribute('maxlength', '17');
    el.setAttribute('autocomplete', 'tel-national');
    el.addEventListener('input', function () {
      var limpo = el.value.replace(/[^0-9+\s.\-]/g, '');   // sem letras
      if (limpo !== el.value) el.value = limpo;
      if (el.value.replace(/\D/g, '').length >= 9) el._tocado = true;
      validar(el);
    });
    el.addEventListener('blur', function () { el._tocado = true; arrumar(el); validar(el, true); });
    el.addEventListener('change', function () { arrumar(el); validar(el); });
    if (el.form) el.form.addEventListener('submit', function () { el._tocado = true; validar(el, true); }, true);
    arrumar(el);
    validar(el);
  }

  function arrancar() { document.querySelectorAll('input[data-contacto]').forEach(iniciar); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', arrancar); else arrancar();
})();
