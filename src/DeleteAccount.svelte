<script>
    import { navigate } from 'svelte-routing';

    const deleteAccount = async () => {
        const confirmDelete = confirm('¿Estás seguro de que quieres eliminar tu cuenta?');
        if (confirmDelete) {
            try {
                const response = await fetch('http://localhost/SVELTE/svelte-login/backend/delete_account.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();
                if (data.status === 'success') {
                    alert('Cuenta desactivada correctamente');
                    navigate('/login');  // Redirigir a la página de login
                } else {
                    alert('Hubo un problema al desactivar la cuenta: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error al contactar con el servidor:', error);
                alert('Error al contactar con el servidor');
            }
        }
    };
</script>

<style>
    button {
        background-color: red;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: darkred;
    }
</style>

<h1>Eliminar cuenta</h1>
<p>¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.</p>

<button on:click={deleteAccount}>Eliminar Cuenta</button>