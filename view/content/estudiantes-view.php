<!-- 
// Instanciamos el controlador
$peticionAjax = false;
require_once "./controllers/proveedorController.php";
$ins_proveedor = new proveedorController();

// Ejecutamos el procesador de acciones (Registrar, Editar, Eliminar)
$ins_proveedor->procesar_proveedor_controlador();

// Pedimos los datos para llenar la tabla
$proveedores_db = $ins_proveedor->listar_proveedores_controlador(); -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Módulo de Proveedores</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-journal-arrow-down" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">MÓDULO DE ESTUDIANTES</p>
                        <h1 class="h3 mb-1">Estudiantes</h1>
                        <p class="text-muted mb-0">Registra tus Estudiantes.</p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">

                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Registrar Nuevo Estudiante</h5>
                        </div>
                        <div class="card-body">
                            <form class="FormularioAjax" action="<?php echo APP_URL; ?>router/requestProveedor.php" method="POST" autocomplete="off" data-form="save" novalidate>
                                <input type="hidden" name="action" value="registrar">

                                <div class="mb-3 form-group">
                                    <label for="nombre" class="form-label fw-bold">Nombre y Apellido</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Juan Pablo." required>
                                </div>

                                <div class="mb-3 form-group">
                                    <label for="telefono" class="form-label fw-bold">Teléfono de Contacto</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ej. 0414-1234567" required>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-submit">
                                        <i class="bi bi-check-lg"></i> Registrar Estudiantes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">REGISTRADOS REGISTRADOS </h5>

                            <div class="d-flex gap-2 search-filter-box">
                                <input type="text" placeholder="Buscar proveedor..." class="form-control form-control-sm search-input">
                                <button class="btn btn-sm btn-outline-secondary btn-filter"><i class="bi bi-funnel"></i> Filtros</button>
                            </div>
                        </div>

                        <div class="card-body p-0 table-responsive">
                            <table class="table table-hover table-vertical-align mb-0 provider-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($proveedores_db)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4 no-data">No hay estudiantes registrados en la base de datos.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($proveedores_db as $prov): ?>
                                            <tr>
                                                <td class="fw-bold"><?php echo $prov['Nombre']; ?></td>
                                                <td><span class="badge-code"><?php echo $prov['Rif_Proveedor']; ?></span></td>
                                                <td><small><?php echo $prov['Direccion']; ?></small></td>
                                                <td class="text-muted"><small><?php echo $prov['Correo']; ?></small></td>
                                                <td><small><?php echo $prov['Telefono']; ?></small></td>
                                                <td class="text-center actions-cell">

                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-action" data-bs-toggle="modal" data-bs-target="#modalEditarProv_<?php echo $prov['ID_Proveedor']; ?>" title="Editar Proveedor">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>

                                                    <div class="modal fade text-start" id="modalEditarProv_<?php echo $prov['ID_Proveedor']; ?>" tabindex="-1" aria-labelledby="modalEditarProvLabel_<?php echo $prov['ID_Proveedor']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalEditarProvLabel_<?php echo $prov['ID_Proveedor']; ?>">Editar Proveedor</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                                </div>

                                                                <form class="FormularioAjax" action="<?php echo APP_URL; ?>router/requestProveedor.php" method="POST" autocomplete="off" data-form="update" novalidate>
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="action" value="editar_proveedor">
                                                                        <input type="hidden" name="id_proveedor" value="<?php echo $prov['ID_Proveedor']; ?>">

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Nombre y Apellido</label>
                                                                            <input type="text" class="form-control" name="nombre" value="<?php echo $prov['Nombre']; ?>" required>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Teléfono de Contacto</label>
                                                                            <input type="text" class="form-control" name="telefono" value="<?php echo $prov['Telefono']; ?>" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <form class="FormularioAjax" action="<?php echo APP_URL; ?>router/requestProveedor.php?action=eliminar&id=<?php echo $prov['ID_Proveedor']; ?>" method="POST" autocomplete="off" data-form="delete">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>

</html>