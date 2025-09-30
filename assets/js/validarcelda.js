     document.addEventListener('DOMContentLoaded', function () {
      const docTipo = document.getElementById('doc_tipo');
      const cuitInput = document.getElementById('cuit');
  
      // Función para validar el campo según el tipo de documento seleccionado
      function validarDocumento() {
        const tipo = docTipo.value;
        const valor = cuitInput.value;
  
        if (tipo === 'DNI') {
          if (!/^\d{8}$/.test(valor)) {
            alert('El DNI debe tener exactamente 8 dígitos numéricos.');
            return false;
          }
        } else if (tipo === 'CUIT' || tipo === 'CUIL') {
          if (!/^\d{11}$/.test(valor)) {
            alert('El CUIT/CUIL debe tener exactamente 11 dígitos numéricos.');
            return false;
          }
        }
        return true;
      }
  
      // Validar al enviar el formulario
      const form = document.forms['alta_cliente'];
      form.addEventListener('submit', function (event) {
        if (!validarDocumento()) {
          event.preventDefault(); // Evitar el envío si la validación falla
        }
      });
    });
  