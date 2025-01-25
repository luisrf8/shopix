<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-4">
            <!-- <h3 class="text-center mb-4">Grupo Ecosta</h3> -->
            <form>
                @csrf
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>
                <button type="submit" class="btn btn-info btn-block">Ingresar</button>
            </form>
            <div class="text-center mt-3">
                <a>¿Olvidaste tu contraseña?</a>
            </div>
            <!-- <div class="text-center mt-2">
                <small>¿No tienes una cuenta? <a href="/register">Regístrate aquí</a></small>
            </div> -->
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script>
    document.querySelector("form").addEventListener("submit", function(event) {
        event.preventDefault();
        
        let formData = new FormData(this);
        let submitButton = this.querySelector("button[type='submit']");
        
        // Cambiar texto a "Cargando..." y deshabilitar el botón
        submitButton.textContent = "Cargando...";
        submitButton.disabled = true;

        fetch("api/login", {
            method: "POST",
            body: formData
        })
        .then(response => {
            return response.json().then(data => {
                console.log("response", data)
                    // Almacena el token y redirige al dashboard
                    localStorage.setItem('authToken', data.token);
                    window.location.href = '/dashboard'; // Redirección al dashboard
            });
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Ocurrió un error");
        })
        .finally(() => {
            // Restaurar el texto original y habilitar el botón
            submitButton.textContent = "Ingresar";
            submitButton.disabled = false;
        });
    });
</script>
