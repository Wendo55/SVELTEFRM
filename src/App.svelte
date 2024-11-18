<script>
    let correo = '';
    let contrasena = '';
    let nuevoNombre = '';
    let usuarioLogueado = '';
    let loggedIn = false;
    let showChangeNameForm = false; // Para mostrar/ocultar el formulario de cambio de nombre
    let showRegisterForm = false; // Para mostrar/ocultar el formulario de registro

    // Login
    const login = async () => {
        if (!correo || !contrasena) {
            alert('Por favor, ingresa tu correo y contraseña');
            return;
        }

        const response = await fetch('http://localhost/SVELTE/svelte-login/backend/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo, contrasena })
        });

        const data = await response.json();
        if (data.status === 'success') {
            usuarioLogueado = data.correo;
            loggedIn = true;
        } else {
            alert(data.message || 'Error al iniciar sesión.');
        }
    };

    // Registrar nuevo usuario
    const register = async () => {
        if (!correo || !contrasena || !nuevoNombre) {
            alert('Por favor, completa todos los campos');
            return;
        }

        const response = await fetch('http://localhost/SVELTE/svelte-login/backend/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo, contrasena, nuevoNombre })
        });

        const data = await response.json();
        if (data.status === 'success') {
            alert('Registro exitoso! Ahora puedes iniciar sesión.');
            showRegisterForm = false; // Cerrar formulario de registro
        } else {
            alert(data.message || 'Error al registrarse.');
        }
    };

    // Cambiar nombre
    const cambiarNombre = async () => {
        if (!nuevoNombre.trim()) {
            alert('Por favor, ingresa un nombre válido.');
            return;
        }

        const response = await fetch('http://localhost/SVELTE/svelte-login/backend/changeName.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo: usuarioLogueado, nuevoNombre })
        });

        const data = await response.json();
        if (data.status === 'success') {
            alert('Nombre cambiado con éxito.');
            usuarioLogueado = nuevoNombre; // Actualizar nombre en la variable
            showChangeNameForm = false; // Ocultar formulario de cambio de nombre
        } else {
            alert(data.message || 'Error al cambiar el nombre.');
        }
    };

    // Desactivar cuenta (actualiza estatus a 0)
    const desactivarCuenta = async () => {
        const confirmDelete = confirm('¿Estás seguro de que deseas desactivar tu cuenta? Esta acción puede ser revertida.');
        if (!confirmDelete) return;

        const response = await fetch('http://localhost/SVELTE/svelte-login/backend/desactivarCuenta.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo: usuarioLogueado })
        });

        const data = await response.json();
        if (data.status === 'success') {
            alert('Cuenta desactivada con éxito.');
            loggedIn = false; // Cerrar sesión
            usuarioLogueado = '';
            correo = '';
            contrasena = '';
            nuevoNombre = '';
        } else {
            alert(data.message || 'Error al desactivar la cuenta.');
        }
    };

    // Cerrar sesión
    const logout = () => {
        loggedIn = false;
        usuarioLogueado = '';
        correo = '';
        contrasena = '';
        nuevoNombre = '';
    };
</script>

<main>
    <h1>Aplicación Principal</h1>

    <!-- Página de Login -->
    {#if !loggedIn}
        <div>
            <h2>Iniciar sesión</h2>
            <input type="email" placeholder="Correo" bind:value={correo} />
            <input type="password" placeholder="Contraseña" bind:value={contrasena} />
            <button on:click={login}>Iniciar sesión</button>
            <button on:click={() => showRegisterForm = !showRegisterForm}>Registrarse</button> <!-- Mostrar formulario de registro -->
        </div>

        <!-- Formulario de registro -->
        {#if showRegisterForm}
            <div>
                <h2>Registrar usuario</h2>
                <input type="email" placeholder="Correo" bind:value={correo} />
                <input type="password" placeholder="Contraseña" bind:value={contrasena} />
                <input type="text" placeholder="Nombre" bind:value={nuevoNombre} />
                <button on:click={register}>Registrar</button>
            </div>
        {/if}
    {/if}

    <!-- Página de Bienvenida -->
    {#if loggedIn}
        <div>
            <h2>Bienvenido, {usuarioLogueado}!</h2>
            <button on:click={() => showChangeNameForm = !showChangeNameForm}>Cambiar Nombre</button>
            <button on:click={logout}>Cerrar sesión</button>
            <button on:click={desactivarCuenta}>Desactivar Cuenta</button> <!-- Botón para desactivar cuenta -->
        </div>

        <!-- Formulario para cambiar el nombre, se muestra cuando `showChangeNameForm` es true -->
        {#if showChangeNameForm}
            <div>
                <h2>Cambiar Nombre</h2>
                <input type="text" placeholder="Nuevo Nombre" bind:value={nuevoNombre} />
                <button on:click={cambiarNombre}>Guardar cambios</button>
            </div>
        {/if}
    {/if}
</main>