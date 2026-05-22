/* ============================================================
   PORTAFOLIO — admin.js
   CRUD operations via AJAX
   ============================================================ */

const API = '../api/';

/* ---- Generic helpers ---- */
function showAlert(containerId, message, type = 'success') {
    const el = document.getElementById(containerId);
    if (!el) return;
    el.innerHTML = `<div class="alert alert-${type === 'success' ? 'success' : 'danger'} py-2 small mb-3">${message}</div>`;
    setTimeout(() => { el.innerHTML = ''; }, 4000);
}

async function apiCall(endpoint, method, data) {
    const res = await fetch(API + endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...data, _method: method }),
    });
    return res.json();
}

async function deleteItem(entity, id, elementId) {
    if (!confirm('¿Eliminar este elemento?')) return;
    try {
        const json = await apiCall(entity + '.php', 'DELETE', { id });
        if (json.success) {
            document.getElementById(elementId)?.remove();
        } else {
            alert(json.message || 'Error al eliminar.');
        }
    } catch {
        alert('Error de conexión.');
    }
}

/* ==============================================================
   BIOGRAFÍA
   ============================================================== */
const bioModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('bioModal'));

function openBioModal() {
    // Populate from current displayed values
    const nombre = document.querySelector('#tab-bio strong')?.textContent || '';
    document.getElementById('bioId').value = '<?php /* server-side id */ ?>';
    bioModal().show();

    // Load via API
    fetch(API + 'biografia.php')
        .then(r => r.json())
        .then(d => {
            if (d.data) {
                document.getElementById('bioId').value          = d.data.id            || '';
                document.getElementById('bioNombre').value      = d.data.nombre        || '';
                document.getElementById('bioCargo').value       = d.data.cargo         || '';
                document.getElementById('bioDesc').value        = d.data.descripcion   || '';
                document.getElementById('bioDescExtra').value   = d.data.descripcion_extra || '';
                document.getElementById('bioEmail').value       = d.data.email         || '';
                document.getElementById('bioFoto').value        = d.data.foto          || '';
            }
        });
}

async function saveBio() {
    const data = {
        id:                document.getElementById('bioId').value,
        nombre:            document.getElementById('bioNombre').value.trim(),
        cargo:             document.getElementById('bioCargo').value.trim(),
        descripcion:       document.getElementById('bioDesc').value.trim(),
        descripcion_extra: document.getElementById('bioDescExtra').value.trim(),
        email:             document.getElementById('bioEmail').value.trim(),
        foto:              document.getElementById('bioFoto').value.trim(),
    };
    if (!data.nombre) { showAlert('bioAlert', 'El nombre es obligatorio.', 'error'); return; }
    try {
        const json = await apiCall('biografia.php', 'POST', data);
        if (json.success) {
            showAlert('bioAlert', json.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('bioAlert', json.message, 'error');
        }
    } catch { showAlert('bioAlert', 'Error de conexión.', 'error'); }
}

/* ==============================================================
   HABILIDADES
   ============================================================== */
const habModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('habModal'));

function openHabModal() {
    document.getElementById('habModalTitle').innerHTML = '<i class="bi bi-tools me-2"></i>Nueva Habilidad';
    document.getElementById('habForm').reset();
    document.getElementById('habId').value = '';
    habModal().show();
}

function editHab(id, data) {
    document.getElementById('habModalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Habilidad';
    document.getElementById('habId').value      = id;
    document.getElementById('habNombre').value  = data.nombre     || '';
    document.getElementById('habIcono').value   = data.icono      || '';
    document.getElementById('habDesc').value    = data.descripcion || '';
    document.getElementById('habOrden').value   = data.orden      || 0;
    habModal().show();
}

async function saveHab() {
    const data = {
        id:          document.getElementById('habId').value,
        nombre:      document.getElementById('habNombre').value.trim(),
        icono:       document.getElementById('habIcono').value.trim() || 'bi bi-code-slash',
        descripcion: document.getElementById('habDesc').value.trim(),
        orden:       parseInt(document.getElementById('habOrden').value) || 0,
    };
    if (!data.nombre) { showAlert('habAlert', 'El nombre es obligatorio.', 'error'); return; }
    try {
        const method = data.id ? 'PUT' : 'POST';
        const json   = await apiCall('habilidades.php', method, data);
        if (json.success) {
            showAlert('habAlert', json.message);
            setTimeout(() => location.reload(), 800);
        } else {
            showAlert('habAlert', json.message, 'error');
        }
    } catch { showAlert('habAlert', 'Error de conexión.', 'error'); }
}

/* ==============================================================
   TECNOLOGÍAS
   ============================================================== */
const tecModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('tecModal'));

function openTecModal() {
    document.getElementById('tecModalTitle').innerHTML = '<i class="bi bi-code-slash me-2"></i>Nueva Tecnología';
    document.getElementById('tecForm').reset();
    document.getElementById('tecId').value = '';
    document.getElementById('tecPorcentaje').value = 50;
    document.getElementById('tecPctLabel').textContent = '50%';
    tecModal().show();
}

function editTec(id, data) {
    document.getElementById('tecModalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Tecnología';
    document.getElementById('tecId').value         = id;
    document.getElementById('tecNombre').value     = data.nombre     || '';
    document.getElementById('tecPorcentaje').value = data.porcentaje || 0;
    document.getElementById('tecPctLabel').textContent = (data.porcentaje || 0) + '%';
    document.getElementById('tecNivel').value      = data.nivel      || 'Intermedio';
    document.getElementById('tecOrden').value      = data.orden      || 0;
    tecModal().show();
}

async function saveTec() {
    const data = {
        id:         document.getElementById('tecId').value,
        nombre:     document.getElementById('tecNombre').value.trim(),
        porcentaje: parseInt(document.getElementById('tecPorcentaje').value) || 0,
        nivel:      document.getElementById('tecNivel').value,
        orden:      parseInt(document.getElementById('tecOrden').value) || 0,
    };
    if (!data.nombre) { showAlert('tecAlert', 'El nombre es obligatorio.', 'error'); return; }
    try {
        const method = data.id ? 'PUT' : 'POST';
        const json   = await apiCall('tecnologias.php', method, data);
        if (json.success) {
            showAlert('tecAlert', json.message);
            setTimeout(() => location.reload(), 800);
        } else {
            showAlert('tecAlert', json.message, 'error');
        }
    } catch { showAlert('tecAlert', 'Error de conexión.', 'error'); }
}

/* ==============================================================
   PROYECTOS
   ============================================================== */
const proModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('proModal'));

function openProModal() {
    document.getElementById('proModalTitle').innerHTML = '<i class="bi bi-folder2 me-2"></i>Nuevo Proyecto';
    document.getElementById('proForm').reset();
    document.getElementById('proId').value = '';
    proModal().show();
}

function editPro(id, data) {
    document.getElementById('proModalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Proyecto';
    document.getElementById('proId').value      = id;
    document.getElementById('proTitulo').value  = data.titulo               || '';
    document.getElementById('proDesc').value    = data.descripcion           || '';
    document.getElementById('proDemo').value    = data.url_demo              || '';
    document.getElementById('proGithub').value  = data.url_github            || '';
    document.getElementById('proTechs').value   = data.tecnologias_usadas    || '';
    document.getElementById('proOrden').value   = data.orden                 || 0;
    document.getElementById('proImagen').value  = data.imagen                || '';
    proModal().show();
}

async function savePro() {
    const data = {
        id:                 document.getElementById('proId').value,
        titulo:             document.getElementById('proTitulo').value.trim(),
        descripcion:        document.getElementById('proDesc').value.trim(),
        url_demo:           document.getElementById('proDemo').value.trim(),
        url_github:         document.getElementById('proGithub').value.trim(),
        tecnologias_usadas: document.getElementById('proTechs').value.trim(),
        orden:              parseInt(document.getElementById('proOrden').value) || 0,
        imagen:             document.getElementById('proImagen').value.trim(),
    };
    if (!data.titulo) { showAlert('proAlert', 'El título es obligatorio.', 'error'); return; }
    try {
        const method = data.id ? 'PUT' : 'POST';
        const json   = await apiCall('proyectos.php', method, data);
        if (json.success) {
            showAlert('proAlert', json.message);
            setTimeout(() => location.reload(), 800);
        } else {
            showAlert('proAlert', json.message, 'error');
        }
    } catch { showAlert('proAlert', 'Error de conexión.', 'error'); }
}
