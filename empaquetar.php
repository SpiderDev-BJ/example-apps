<?php
// 1. Mostrar cualquier advertencia o error en pantalla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Liberar límites de tiempo y memoria para escaneo completo
set_time_limit(0);
ini_set('memory_limit', '1024M');

$directorio_raiz = __DIR__;
$archivo_destino = __DIR__ . '/mi_sistema_completo.txt';

// Inicializar el archivo en disco
$encabezado  = "=================================================================\n";
$encabezado .= "SISTEMA DE GESTIÓN - MULTISERVICIOS SIAMO C.A.\n";
$encabezado .= "FECHA DE EMPAQUETADO: " . date('Y-m-d H:i:s') . "\n";
$encabezado .= "=================================================================\n\n";
file_put_contents($archivo_destino, $encabezado);

// Carpetas de tu arquitectura MVC a incluir
$carpetas_incluir = ['config', 'controller', 'model', 'router', 'view'];
$archivos_raiz    = ['index.php'];

// Extensiones de código que deben ser leídas
$extensiones_permitidas = ['php', 'sql', 'js', 'css'];

// Carpetas de librerías de terceros a ignorar (NUNCA incluir 'js' genérico aquí)
$patrones_ignorar = [
    '/vendors/',
    '/vendor/',
    '/bootstrap/',
    '/bootstrap-icons/',
    '/fontawesome/',
    '/fontawesome-free/',
    '/jquery/',
    '/chart.js/',
    '/datatables/',
    '/fpdf/font/',
    '/assets/images/',
    '/assets/img/'
];

$archivos_contados = 0;
$peso_total_bytes = 0;

// 3. Procesar archivos sueltos de la raíz
foreach ($archivos_raiz as $arc) {
    $ruta = $directorio_raiz . '/' . $arc;
    if (file_exists($ruta)) {
        $codigo = file_get_contents($ruta);
        $peso_total_bytes += strlen($codigo);
        $bloque = "\n\n" . str_repeat('=', 70) . "\nARCHIVO: {$arc}\n" . str_repeat('=', 70) . "\n\n" . $codigo;
        file_put_contents($archivo_destino, $bloque, FILE_APPEND);
        $archivos_contados++;
    }
}

// 4. Procesar carpetas del sistema recursivamente
foreach ($carpetas_incluir as $carpeta) {
    $ruta_carpeta = $directorio_raiz . '/' . $carpeta;
    if (!is_dir($ruta_carpeta)) continue;

    try {
        $iterador = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($ruta_carpeta, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterador as $archivo) {
            if ($archivo->isDir()) continue;

            $ruta_completa = $archivo->getPathname();
            $ruta_relativa = str_replace($directorio_raiz . DIRECTORY_SEPARATOR, '', $ruta_completa);
            $ruta_normalizada = str_replace('\\', '/', $ruta_relativa);

            // Filtrar librerías externas o assets pesados
            $ignorar = false;
            foreach ($patrones_ignorar as $patron) {
                if (stripos('/' . $ruta_normalizada, $patron) !== false) {
                    $ignorar = true;
                    break;
                }
            }
            if ($ignorar) continue;

            // Validar extensión
            $ext = strtolower($archivo->getExtension());
            if (!in_array($ext, $extensiones_permitidas)) continue;

            $codigo = file_get_contents($ruta_completa);
            if (trim($codigo) === '') continue;

            $peso_total_bytes += strlen($codigo);

            // Escribir en chunks append para no desbordar memoria
            $bloque = "\n\n" . str_repeat('=', 70) . "\nARCHIVO: {$ruta_normalizada}\n" . str_repeat('=', 70) . "\n\n" . $codigo;
            file_put_contents($archivo_destino, $bloque, FILE_APPEND);
            $archivos_contados++;
        }
    } catch (Exception $e) {
        continue;
    }
}

$peso_final_mb = round($peso_total_bytes / (1024 * 1024), 2);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Empaquetado Listo</title></head>";
echo "<body style='font-family: sans-serif; background: #0f172a; color: #f8fafc; padding: 40px; text-align: center;'>";
echo "<div style='background: #1e293b; padding: 30px; border-radius: 12px; display: inline-block; max-width: 600px; border: 1px solid #334155;'>";
echo "<h2 style='color: #4ade80; margin-top: 0;'>¡Empaquetado completado con éxito!</h2>";
echo "<p style='font-size: 1.1rem;'>Se empaquetaron <strong>{$archivos_contados} archivos</strong> de código limpio.</p>";
echo "<p style='color: #94a3b8;'>Peso total del texto generado: <strong style='color: #fbbf24;'>{$peso_final_mb} MB</strong></p>";
echo "<p style='color: #94a3b8;'>Ubicación del archivo:<br><strong style='color: #38bdf8;'>mi_sistema_completo.txt</strong></p>";
echo "</div></body></html>";
