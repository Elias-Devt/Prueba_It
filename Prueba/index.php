<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRUEBA IT</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }

        button {
            background-color: #007bff; 
            color: white; 
            border: none;
            padding: 10px 15px; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #0056b3; 
            transform: translateY(-2px);
        }

        .btn-secondary, .btn-primary {
            padding: 10px 15px; 
        }

        .editBtn, .deleteBtn {
            background-color: #28a745; 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            border-radius: 5px; 
            transition: background-color 0.3s, transform 0.3s; 
        }

        .editBtn:hover {
            background-color: #218838; 
            transform: translateY(-2px); 
        }

        .deleteBtn {
            background-color: #dc3545; 
        }

        .deleteBtn:hover {
            background-color: #c82333; 
        }
    </style>
</head>
<body>

<h2>PRUEBA IT (CRUD)</h2>

<div>
    <h3>Añadir Usuario</h3>
    <input type="text" id="nombre" placeholder="Nombre">
    <input type="text" id="apellidos" placeholder="Apellidos">
    <input type="email" id="correo" placeholder="Email">
    <button id="addusuario">Añadir</button>
</div>

<h3>LISTA DE USUARIOS</h3>

<table>
    <thead>
        <tr>
            <th>NOMBRE</th>
            <th>APELLIDOS</th>
            <th>EMAIL</th>
            <th>ACCIONES</th>
        </tr>
    </thead>
    <tbody id="tab-usuarios">
        <!--Uso de JQuery-->
    </tbody>
</table>

<!-- Modal para editar usuarios -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId"> 
                    <div class="form-group">
                        <label for="editNombre">Nombre</label>
                        <input type="text" class="form-control" id="editNombre" required>
                    </div>
                    <div class="form-group">
                        <label for="editApellidos">Apellidos</label>
                        <input type="text" class="form-control" id="editApellidos" required>
                    </div>
                    <div class="form-group">
                        <label for="editCorreo">Correo</label>
                        <input type="email" class="form-control" id="editCorreo" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveEdit">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        usuariostab();

        // Crear usuarios
        $('#addusuario').click(function() {
            const nombre = $('#nombre').val();
            const apellidos = $('#apellidos').val();
            const correo = $('#correo').val();

            $.ajax({
                url: 'apis.php',
                type: 'POST',
                data: { action: 'crear', nombre: nombre, apellidos: apellidos, correo: correo },
                success: function(response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Usuario Agregado',
                            text: 'El usuario se ha agregado correctamente.'
                        });
                        $('#nombre').val('');
                        $('#apellidos').val('');
                        $('#correo').val('');
                        usuariostab(); 
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: jsonResponse.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al agregar usuario',
                        text: xhr.responseText 
                    });
                }
            });

        });

        // Obtener usuarios
        function usuariostab() {
            $.ajax({
                url: 'apis.php',
                type: 'POST',
                data: { action: 'obtener' },
                success: function(response) {
                    try {
                        const usuarios = JSON.parse(response);
                        let rows = '';
                        usuarios.forEach(function(usuario) {
                            rows += `
                                <tr>
                                    <td>${usuario.nombre}</td>
                                    <td>${usuario.apellidos}</td>
                                    <td>${usuario.correo}</td>
                                    <td>
                                        <button class="editBtn" data-id="${usuario.id}" data-nombre="${usuario.nombre}" data-apellidos="${usuario.apellidos}" data-correo="${usuario.correo}">Editar</button>
                                        <button class="deleteBtn" data-id="${usuario.id}">Eliminar</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#tab-usuarios').html(rows); // Poner las filas en la tabla
                    } catch (e) {
                        console.error("Error al procesar la respuesta JSON:", e);
                        console.error("Respuesta recibida:", response);
                    }
                }
            });
        }

        // Editar usuario
        $(document).on('click', '.editBtn', function() {
            const userId = $(this).data('id');
            const userNombre = $(this).data('nombre');
            const userApellidos = $(this).data('apellidos');
            const userCorreo = $(this).data('correo');

            $('#editUserId').val(userId);
            $('#editNombre').val(userNombre);
            $('#editApellidos').val(userApellidos);
            $('#editCorreo').val(userCorreo);

            $('#editUserModal').modal('show');
        });

        // Guardar cambios al editar usuario
        $('#saveEdit').click(function() {
            const userId = $('#editUserId').val();
            const nombre = $('#editNombre').val();
            const apellidos = $('#editApellidos').val();
            const correo = $('#editCorreo').val();

            $.ajax({
                url: 'apis.php',
                type: 'POST',
                data: { 
                    action: 'editar', 
                    id: userId,
                    nombre: nombre, 
                    apellidos: apellidos, 
                    correo: correo 
                },
                success: function(response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                    Swal.fire({
                        title: "Usuario Modificado",
                        text: "De click para continuar",
                        icon: "success"
                    });
                    $('#editUserForm')[0].reset(); 
                    $('#editUserModal').modal('hide'); 
                    usuariostab();
                    }  else {
                        Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message 
                    });
                    }
                },
                error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al actualizar usuario',
                    text: xhr.responseText 
                });
            }
            });
        });
        
         // Eliminar usuario
         $(document).on('click', '.deleteBtn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'apis.php',
                            type: 'POST',
                            data: { action: 'eliminar', id: id },
                            success: function(response) {
                                Swal.fire(
                                    'Eliminado!',
                                    'El usuario ha sido eliminado.',
                                    'success'
                                );
                                usuariostab(); 
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al eliminar usuario',
                                    text: xhr.responseText
                                });
                            }
                        });
                    }
                });
            });
        });
</script>
</body>
</html>
