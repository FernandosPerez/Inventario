var chartCampus, chartTipos, chartTendencia;

var campusLabels = ['San Juan','SJR5','Aculco','Tecamac','Tepeji','Atlacomulco','Nopala','En Línea','Corporativo'];
var campusKeys   = ['sanjuan','sanjuan5','aculco','tecamac','tepeji','atlacomulco','nopala','enlinea','corporativo'];
var campusColors = ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#858796','#5a5c69','#fd7e14','#6f42c1'];
var tipoNombres  = ['Alta','Ingreso','Egreso','Transferencia','Actualización','Baja'];
var tipoColors   = ['#28a745','#007bff','#ffc107','#17a2b8','#6c757d','#dc3545'];

// ── Fecha local ─────────────────────────────────────────────────────────────
function localDate(d) {
    var y   = d.getFullYear();
    var m   = String(d.getMonth() + 1).padStart(2, '0');
    var day = String(d.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + day;
}

function ajaxPost(data, cb) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'funciones/reportes_inventario.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () { if (xhr.readyState === 4) cb(xhr.responseText); };
    xhr.send(data);
}

// ── Getters ─────────────────────────────────────────────────────────────────
function getDesde()  { return document.getElementById('fDesde').value; }
function getHasta()  { return document.getElementById('fHasta').value; }
function getCampus() { return document.getElementById('fCampus').value; }

function baseParams() {
    return 'campus=' + encodeURIComponent(getCampus())
         + '&desde='  + encodeURIComponent(getDesde())
         + '&hasta='  + encodeURIComponent(getHasta());
}

// ── KPI cards ───────────────────────────────────────────────────────────────
function cargarKPIs() {
    ajaxPost('op=1&' + baseParams(), function (txt) {
        var d = JSON.parse(txt);
        document.getElementById('kpiArticulos').textContent   = d.articulos.toLocaleString('es-MX');
        document.getElementById('kpiStock').textContent       = d.stock.toLocaleString('es-MX');
        document.getElementById('kpiMovimientos').textContent = d.movimientos.toLocaleString('es-MX');
        document.getElementById('kpiEgresos').textContent     = d.egresos.toLocaleString('es-MX');
    });
}

// ── Modal de detalle mensual ─────────────────────────────────────────────────
function verMovimientosMes(tipo) {
    var div = document.getElementById('contenidoHistorial');
    div.innerHTML = '<div class="text-center py-5">'
        + '<div class="spinner-border text-primary" style="width:3rem;height:3rem;"></div>'
        + '<p class="mt-3 text-muted">Cargando...</p></div>';
    $('#modalHistorial').modal('show');
    ajaxPost('op=6&tipo=' + tipo + '&' + baseParams(), function (txt) { div.innerHTML = txt; });
}

// ── Bar chart: stock por campus ──────────────────────────────────────────────
function cargarChartCampus() {
    ajaxPost('op=2&campus=' + encodeURIComponent(getCampus()), function (txt) {
        var d   = JSON.parse(txt);
        var cam = getCampus();
        var labels, values, colors;

        var campusIdToKey = {
            '1':'sanjuan','13':'sanjuan5','2':'aculco','3':'tecamac',
            '5':'tepeji','4':'atlacomulco','6':'nopala','7':'enlinea','8':'corporativo'
        };

        if (cam && campusIdToKey[cam]) {
            var key = campusIdToKey[cam];
            var idx = campusKeys.indexOf(key);
            labels = [campusLabels[idx]];
            values = [parseFloat(d[key]) || 0];
            colors = [campusColors[idx]];
        } else {
            labels = campusLabels;
            values = campusKeys.map(function (k) { return parseFloat(d[k]) || 0; });
            colors = campusColors;
        }

        if (chartCampus) chartCampus.destroy();
        chartCampus = new Chart(document.getElementById('chartCampus'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Stock actual',
                    data: values,
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: function (c) { return ' ' + c.raw.toLocaleString('es-MX') + ' unidades'; } } }
                },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    });
}

// ── Doughnut: tipos de movimiento ────────────────────────────────────────────
function cargarChartTipos() {
    ajaxPost('op=3&' + baseParams(), function (txt) {
        var rows = JSON.parse(txt);
        if (!rows || !rows.length) {
            document.getElementById('chartTipos').parentElement.innerHTML =
                '<p class="text-muted text-center py-5">Sin movimientos en el período</p>';
            return;
        }
        var labels = [], data = [], colors = [];
        rows.forEach(function (r) {
            var ti = parseInt(r.tipo);
            labels.push(tipoNombres[ti] || 'Tipo ' + ti);
            data.push(parseInt(r.total));
            colors.push(tipoColors[ti] || '#999');
        });
        if (chartTipos) chartTipos.destroy();
        chartTipos = new Chart(document.getElementById('chartTipos'), {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: data, backgroundColor: colors, borderWidth: 2, hoverOffset: 8 }] },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 14 } },
                    tooltip: { callbacks: { label: function (c) { return ' ' + c.label + ': ' + c.raw; } } }
                }
            }
        });
    });
}

// ── Line chart: tendencia mensual ────────────────────────────────────────────
function cargarChartTendencia() {
    ajaxPost('op=4&' + baseParams(), function (txt) {
        var rows = JSON.parse(txt);
        if (!rows || !rows.length) {
            var canvas = document.getElementById('chartTendencia');
            canvas.parentElement.innerHTML = '<p class="text-muted text-center py-5">Sin movimientos en el período seleccionado</p>';
            return;
        }
        var mesesSet = {};
        rows.forEach(function (r) { mesesSet[r.mes] = true; });
        var labels = Object.keys(mesesSet).sort();

        var datasets = {};
        rows.forEach(function (r) {
            var ti = parseInt(r.tipo);
            if (!datasets[ti]) {
                datasets[ti] = {
                    label: tipoNombres[ti] || 'Tipo ' + ti,
                    data: {},
                    borderColor: tipoColors[ti] || '#999',
                    backgroundColor: (tipoColors[ti] || '#999') + '22',
                    tension: 0.4,
                    fill: false,
                    pointRadius: 4
                };
            }
            datasets[ti].data[r.mes] = parseInt(r.total);
        });

        var dsArr = Object.values(datasets).map(function (ds) {
            return {
                label: ds.label,
                data: labels.map(function (m) { return ds.data[m] || 0; }),
                borderColor: ds.borderColor,
                backgroundColor: ds.backgroundColor,
                tension: ds.tension,
                fill: ds.fill,
                pointRadius: ds.pointRadius
            };
        });

        if (chartTendencia) chartTendencia.destroy();
        chartTendencia = new Chart(document.getElementById('chartTendencia'), {
            type: 'line',
            data: { labels: labels, datasets: dsArr },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    });
}

// ── Modal historial de artículo ──────────────────────────────────────────────
function verHistorial(id) {
    var div = document.getElementById('contenidoHistorial');
    div.innerHTML = '<div class="text-center py-5">'
        + '<div class="spinner-border text-primary" style="width:3rem;height:3rem;"></div>'
        + '<p class="mt-3 text-muted">Cargando historial...</p></div>';
    $('#modalHistorial').modal('show');
    ajaxPost('op=5&articulo=' + id, function (txt) { div.innerHTML = txt; });
}

// ── Actualizar todo ──────────────────────────────────────────────────────────
function actualizarTodo() {
    cargarKPIs();
    cargarChartCampus();
    cargarChartTipos();
    cargarChartTendencia();
}

function actualizarGraficas() { actualizarTodo(); }

// ── Inicio ───────────────────────────────────────────────────────────────────
(function init() {
    var today = new Date();
    var from  = new Date(today.getFullYear(), 0, 1);

    document.getElementById('fDesde').value = localDate(from);
    document.getElementById('fHasta').value = localDate(today);

    cargarKPIs();
    cargarChartCampus();
    cargarChartTipos();
    cargarChartTendencia();
})();