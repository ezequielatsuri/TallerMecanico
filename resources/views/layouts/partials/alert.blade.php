@if (session('success'))
<script>
    let message = "{{ session('success') }}";
    Swal.fire({
        icon: 'success',
        title: message,
        position: 'center',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            popup: 'swal-wide'  // Clase CSS para ajustar el ancho
        },
        didOpen: (popup) => {
            popup.addEventListener('mouseenter', Swal.stopTimer)
            popup.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
</script>

<style>
    .swal-wide {
        width: 400px; /* Ajusta el ancho del cuadro de SweetAlert */
        height: 200px;  
    }
</style>
@endif
