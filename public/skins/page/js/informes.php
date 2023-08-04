<script>
    
// Función que se ejecutará al enviar el formulario
function onSubmitForm() {
  const contentloader = document.getElementById('content-loader')
  const loader = document.getElementById('loader')
  contentloader.style.display = 'flex'
  loader.style.display = 'block'
}
document.getElementById('form-viaticos').addEventListener('submit', onSubmitForm);
document.getElementById('form-facturacion').addEventListener('submit', onSubmitForm);


</script>