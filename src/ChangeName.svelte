<script>
    import { navigate } from 'svelte-routing';
    let nuevoNombre = '';  // Variable que almacena el nuevo nombre que el usuario ingresa

    const cambiarNombre = async () => {
        const correo = localStorage.getItem('usuario'); // Obtiene el correo del usuario desde localStorage
        if (!correo) {
            alert('Error: No se encontró el correo del usuario en localStorage.');
            return;
        }

        if (!nuevoNombre.trim()) {
            alert('Por favor, ingresa un nombre válido.');
            return;
        }

        try {
            const response = await fetch('http://localhost/SVELTE/svelte-login/backend/changeName.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify({ correo, nuevoNombre })  // Envia los datos al servidor en formato JSON
            });

            const data = await response.json();  // Obtiene la respuesta JSON del servidor

            if (data.status === 'success') {
                alert('Nombre cambiado con éxito.');
                navigate('/welcome');  // Redirige al usuario a la página de bienvenida
            } else {
                alert(data.message || 'Error al cambiar el nombre.');
            }
        } catch (error) {
            alert('Error al contactar con el servidor.');
        }
    };
</script>

<main>
    <h1>Cambiar nombre de usuario</h1>
    <input
        type="text"
        placeholder="Nuevo nombre"
        bind:value={nuevoNombre} 
    />
    <button on:click={cambiarNombre}>Guardar cambios</button>
    <button on:click={() => navigate('/welcome')}>Cancelar</button>
</main>

<style>
    main {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    input {
        padding: 10px;
        margin: 10px 0;
        width: 200px;
    }
    button {
        margin-top: 10px;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }
</style>